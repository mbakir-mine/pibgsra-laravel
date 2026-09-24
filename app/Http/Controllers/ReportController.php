<?php

namespace App\Http\Controllers;

use App\Models\FamilyFeeCharge;
use App\Models\Payment;
use App\Models\UserRole;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now()->endOfMonth();
        $schoolIds = $request->user()->accessibleSchoolIds();
        $user = $request->user();
        $familyIds = $request->user()->accessibleFamilyIds();
        $scope = fn ($query) => $request->user()->isParent()
            ? $query->whereIn('family_id', $familyIds)
            : $query->whereIn('school_id', $schoolIds);

        $successfulPayments = $scope(Payment::query())
            ->where('status', 'SUCCESS')
            ->whereBetween('paid_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()]);

        return view('reports.index', [
            'from' => $from,
            'to' => $to,
            'totalPayments' => (clone $successfulPayments)->sum('amount'),
            'paymentCount' => (clone $successfulPayments)->count(),
            'outstanding' => $scope(FamilyFeeCharge::query())
                ->where('balance_amount', '>', 0)
                ->sum('balance_amount'),
            'cancelledAmount' => $scope(Payment::query())->where('status', 'CANCELLED')->whereBetween('updated_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])->sum('amount'),
            'cancelledCount' => $scope(Payment::query())->where('status', 'CANCELLED')->whereBetween('updated_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])->count(),
            'schools' => $scope(Payment::query())->selectRaw('school_id, COUNT(*) as payment_count, SUM(amount) as payment_total')->with('school:id,name,district')->where('status', 'SUCCESS')->whereBetween('paid_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])->groupBy('school_id')->orderByDesc('payment_total')->get(),
            'scopeLabel' => $user->platformScopeLabel(),
            'roleLabel' => match ($user->primaryRole()?->role) {
                UserRole::OWNER => 'Pemilik sistem', UserRole::STATE_ADMIN => 'Pentadbir negeri', UserRole::DISTRICT_ADMIN => 'Pentadbir daerah', UserRole::SCHOOL_ADMIN => 'Pentadbir sekolah', UserRole::HEADMASTER => 'Guru besar', UserRole::PARENT => 'Ibu bapa / penjaga', default => 'Pengguna',
            },
        ]);
    }

    public function export(Request $request)
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now()->endOfMonth();

        $payments = Payment::with(['school', 'family', 'receipt'])
            ->when($request->user()->isParent(), fn ($q) => $q->whereIn('family_id', $request->user()->accessibleFamilyIds()))
            ->when(! $request->user()->isParent(), fn ($q) => $q->whereIn('school_id', $request->user()->accessibleSchoolIds()))
            ->where('status', 'SUCCESS')
            ->whereBetween('paid_at', [$from->startOfDay(), $to->endOfDay()])
            ->get();

        return response()->streamDownload(function () use ($payments) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Tarikh', 'Sekolah', 'Keluarga', 'Amaun', 'Status', 'Kaedah', 'Resit']);

            foreach ($payments as $payment) {
                fputcsv($out, [
                    $payment->paid_at?->format('Y-m-d H:i:s'),
                    $payment->school?->name,
                    $payment->family?->name,
                    $payment->amount,
                    $payment->status,
                    $payment->method,
                    $payment->receipt?->receipt_number,
                ]);
            }

            fclose($out);
        }, 'laporan-bayaran.csv', ['Content-Type' => 'text/csv']);
    }
}
