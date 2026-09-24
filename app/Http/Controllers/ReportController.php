<?php

namespace App\Http\Controllers;

use App\Models\FamilyFeeCharge;
use App\Models\Payment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now()->endOfMonth();
        $schoolIds = $request->user()->accessibleSchoolIds();

        $successfulPayments = Payment::whereIn('school_id', $schoolIds)
            ->where('status', 'SUCCESS')
            ->whereBetween('paid_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()]);

        return view('reports.index', [
            'from' => $from,
            'to' => $to,
            'totalPayments' => (clone $successfulPayments)->sum('amount'),
            'paymentCount' => (clone $successfulPayments)->count(),
            'outstanding' => FamilyFeeCharge::whereIn('school_id', $schoolIds)
                ->where('balance_amount', '>', 0)
                ->sum('balance_amount'),
        ]);
    }

    public function export(Request $request)
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now()->endOfMonth();

        $payments = Payment::with(['family', 'receipt'])
            ->whereIn('school_id', $request->user()->accessibleSchoolIds())
            ->where('status', 'SUCCESS')
            ->whereBetween('paid_at', [$from->startOfDay(), $to->endOfDay()])
            ->get();

        return response()->streamDownload(function () use ($payments) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Tarikh', 'Keluarga', 'Amaun', 'Status', 'Resit']);

            foreach ($payments as $payment) {
                fputcsv($out, [
                    $payment->paid_at?->format('Y-m-d H:i:s'),
                    $payment->family?->name,
                    $payment->amount,
                    $payment->status,
                    $payment->receipt?->receipt_number,
                ]);
            }

            fclose($out);
        }, 'laporan-bayaran.csv', ['Content-Type' => 'text/csv']);
    }
}
