<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role->name === 'Owner') {
        return redirect()->route('owner.dashboard');
    } elseif ($user->role->name === 'Kasir') {
        return redirect()->route('kasir.dashboard');
    }
    abort(403);
})->middleware(['auth', 'verified'])->name('dashboard');

// Role: Owner
Route::middleware(['auth', 'role:Owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'index'])->name('dashboard');
    
    Route::resource('inventory', InventoryController::class);
    Route::post('/inventory/{inventory}/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');
    Route::post('/inventory/supplier', [InventoryController::class, 'storeSupplier'])->name('inventory.supplier.store');
    
    Route::resource('menu', MenuController::class);
    Route::resource('category', MenuController::class); // Reusing MenuController or separate? I'll use separate if needed.
    
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.excel');

    Route::resource('users', \App\Http\Controllers\UserController::class);
});

// Role: Kasir
Route::middleware(['auth', 'role:Kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [KasirController::class, 'index'])->name('dashboard');
    
    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');
    
    Route::get('/history', [OrderController::class, 'history'])->name('history');
    Route::get('/order/{transaction}/receipt', [OrderController::class, 'receipt'])->name('receipt');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
