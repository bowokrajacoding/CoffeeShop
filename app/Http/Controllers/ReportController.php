<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now()->endOfDay();

        $transactions = Transaction::with('kasir', 'promo')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRevenue = $transactions->sum('grand_total');
        $totalOrders = $transactions->count();

        return view('owner.reports.index', compact('transactions', 'totalRevenue', 'totalOrders', 'startDate', 'endDate'));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now()->endOfDay();

        $transactions = Transaction::with('kasir')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $pdf = Pdf::loadView('owner.reports.pdf', [
            'transactions' => $transactions,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalRevenue' => $transactions->sum('grand_total')
        ]);

        return $pdf->download('laporan-transaksi-' . now()->format('Ymd') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now()->endOfDay();

        $transactions = Transaction::with('kasir')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        return Excel::download(new \App\Exports\TransactionsExport($transactions), 'laporan-transaksi-' . now()->format('Ymd') . '.xlsx');
    }
}
