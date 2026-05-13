<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        $totalTransactions = Transaction::whereDate('created_at', $today)->count();
        $totalIncome = Transaction::whereDate('created_at', $today)->sum('grand_total');
        $pendingOrders = Transaction::where('status', 'pending')->whereDate('created_at', $today)->count();

        $recentTransactions = Transaction::where('kasir_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('kasir.dashboard', compact('totalTransactions', 'totalIncome', 'pendingOrders', 'recentTransactions'));
    }
}
