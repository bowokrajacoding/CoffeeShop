<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Menu;
use App\Models\Inventory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        // KPI Cards
        $incomeToday = Transaction::whereDate('created_at', $today)->sum('grand_total');
        $incomeWeek = Transaction::whereBetween('created_at', [$thisWeek, Carbon::now()])->sum('grand_total');
        $incomeMonth = Transaction::whereBetween('created_at', [$thisMonth, Carbon::now()])->sum('grand_total');
        
        $totalTransactions = Transaction::whereDate('created_at', $today)->count();
        
        $bestSeller = TransactionDetail::select('menu_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('menu_id')
            ->orderBy('total_qty', 'desc')
            ->with('menu')
            ->first();

        $lowStockCount = Inventory::whereColumn('stock_qty', '<=', 'min_threshold')->count();
        $lowStockItems = Inventory::whereColumn('stock_qty', '<=', 'min_threshold')->get();

        // Charts Data
        $incomeData = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(grand_total) as total')
        )
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        $categoryData = TransactionDetail::join('menus', 'transaction_details.menu_id', '=', 'menus.id')
            ->join('categories', 'menus.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(transaction_details.qty) as total_qty'))
            ->groupBy('categories.name')
            ->get();

        $recentTransactions = Transaction::with('kasir')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('owner.dashboard', compact(
            'incomeToday', 'incomeWeek', 'incomeMonth', 
            'totalTransactions', 'bestSeller', 'lowStockCount', 'lowStockItems',
            'incomeData', 'categoryData', 'recentTransactions'
        ));
    }
}
