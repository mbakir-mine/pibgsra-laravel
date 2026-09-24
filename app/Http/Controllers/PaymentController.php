<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Family;
use App\Models\FamilyFeeCharge;
use App\Models\Payment;
use App\Models\PaymentAllocation;
use App\Models\Receipt;
use App\Models\ReceiptSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->isParent()) {
            $familyIds = $this->parentFamilyIds($request);

            return view('payments.parent-index', [
                'families' => Family::with(['school', 'students', 'charges.feeCategory', 'payments.receipt'])
                    ->whereIn('id', $familyIds)
                    ->get(),
            ]);
        }

        return view('payments.index', [
            'payments' => Payment::with(['school', 'family', 'receipt'])
                ->whereIn('school_id', $request->user()->accessibleSchoolIds())
                ->latest()
                ->paginate(15),
        ]);
    }

    public function create(Request $request)
    {
        if ($request->user()->isParent()) {
            return view('payments.parent-create', [
                'families' => Family::with(['school', 'students', 'charges.feeCategory'])
                    ->whereIn('id', $this->parentFamilyIds($request))
                    ->get(),
            ]);
        }

        $schoolIds = $request->user()->accessibleSchoolIds();

        return view('payments.create', [
            'families' => Family::with('school')
                ->whereIn('school_id', $schoolIds)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $schoolIds = $request->user()->accessibleSchoolIds()->all();

        $familyRule = 'required|exists:families,id';
        if ($request->user()->isParent()) {
            $familyRule = Rule::in($this->parentFamilyIds($request)->all());
        }

        $data = $request->validate([
            'school_id' => ['required', Rule::in($schoolIds)],
            'family_id' => $familyRule,
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|in:DIRECT_FPX_DUITNOW,MANUAL',
        ]);

        $payment = Payment::create($data + [
            'status' => 'PENDING',
            'gateway_provider' => $data['method'] === 'DIRECT_FPX_DUITNOW' ? 'BANK_DIRECT_UAT' : null,
            'gateway_reference' => 'PIBGSRA-'.now()->format('YmdHis').'-'.random_int(1000, 9999),
        ]);

        if ($payment->method === 'DIRECT_FPX_DUITNOW') {
            $payment->update([
                'checkout_url' => route('payments.uat-checkout', $payment),
            ]);

            return redirect()->route('payments.uat-checkout', $payment);
        }

        $this->markPaymentSuccessful($payment, 'MANUAL-'.$payment->id);

        return redirect()->route('payments.index')->with('status', 'Bayaran manual dan resit berjaya direkodkan.');
    }

    public function uatCheckout(Payment $payment)
    {
        $this->authorizePaymentAccess($payment);

        return view('payments.uat-checkout', ['payment' => $payment->load(['school', 'family'])]);
    }

    public function uatSuccess(Payment $payment)
    {
        $this->authorizePaymentAccess($payment);

        if ($payment->status !== 'SUCCESS') {
            $this->markPaymentSuccessful($payment, 'UAT-'.$payment->gateway_reference);
        }

        return redirect()->route('payments.index')->with('status', 'Bayaran UAT berjaya. Resit telah dijana.');
    }

    private function markPaymentSuccessful(Payment $payment, string $transactionId): void
    {
        DB::transaction(function () use ($payment, $transactionId) {
            $payment->refresh();

            $payment->update([
                'status' => 'SUCCESS',
                'gateway_transaction_id' => $transactionId,
                'paid_at' => now(),
            ]);

            $remaining = (float) $payment->amount;

            $charges = FamilyFeeCharge::where('school_id', $payment->school_id)
                ->where('family_id', $payment->family_id)
                ->where('balance_amount', '>', 0)
                ->orderBy('due_date')
                ->lockForUpdate()
                ->get();

            foreach ($charges as $charge) {
                if ($remaining <= 0) {
                    break;
                }

                $allocated = min($remaining, (float) $charge->balance_amount);

                PaymentAllocation::create([
                    'school_id' => $payment->school_id,
                    'payment_id' => $payment->id,
                    'family_fee_charge_id' => $charge->id,
                    'amount' => $allocated,
                ]);

                $newBalance = (float) $charge->balance_amount - $allocated;

                $charge->update([
                    'paid_amount' => (float) $charge->paid_amount + $allocated,
                    'balance_amount' => $newBalance,
                    'status' => $newBalance <= 0 ? 'PAID' : 'PARTIAL',
                ]);

                $remaining -= $allocated;
            }

            $year = now()->year;
            $sequence = ReceiptSequence::firstOrCreate(
                ['school_id' => $payment->school_id, 'receipt_year' => $year],
                ['last_number' => 0]
            );
            $sequence->increment('last_number');

            Receipt::firstOrCreate(
                ['payment_id' => $payment->id],
                [
                    'school_id' => $payment->school_id,
                    'receipt_number' => $year.'-'.str_pad((string) $sequence->last_number, 6, '0', STR_PAD_LEFT),
                    'issued_at' => now(),
                ]
            );

            AuditLog::create([
                'school_id' => $payment->school_id,
                'actor_user_id' => request()->user()?->id,
                'action' => 'created',
                'entity_type' => Payment::class,
                'entity_id' => $payment->id,
                'new_values' => [
                    'amount' => $payment->amount,
                    'status' => 'SUCCESS',
                    'method' => $payment->method,
                    'gateway_transaction_id' => $transactionId,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });
    }

    private function authorizePaymentAccess(Payment $payment): void
    {
        abort_unless(
            request()->user()->accessibleSchoolIds()->contains($payment->school_id),
            403,
            'Akses bayaran tidak dibenarkan.'
        );
    }

    private function parentFamilyIds(Request $request)
    {
        return Family::whereHas('guardians', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->pluck('families.id');
    }
}
