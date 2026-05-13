<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryLog;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $items = Inventory::with('supplier')->get();
        $suppliers = Supplier::all();
        return view('owner.inventory.index', compact('items', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required|in:kopi,non_kopi,snack,makanan_berat',
            'unit' => 'required',
            'stock_qty' => 'required|numeric',
            'min_threshold' => 'required|numeric',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        Inventory::create($request->all());

        return redirect()->back()->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function adjust(Request $request, Inventory $inventory)
    {
        $request->validate([
            'qty' => 'required|numeric',
            'type' => 'required|in:in,out',
            'note' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $inventory) {
            if ($request->type === 'in') {
                $inventory->increment('stock_qty', $request->qty);
            } else {
                $inventory->decrement('stock_qty', $request->qty);
            }

            InventoryLog::create([
                'inventory_id' => $inventory->id,
                'type' => $request->type,
                'qty' => $request->qty,
                'note' => $request->note,
                'created_by' => Auth::id(),
            ]);
        });

        return redirect()->back()->with('success', 'Stok berhasil diperbarui.');
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required|in:kopi,non_kopi,snack,makanan_berat',
            'unit' => 'required',
            'stock_qty' => 'required|numeric',
            'min_threshold' => 'required|numeric',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        $inventory->update($request->all());

        return redirect()->back()->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function storeSupplier(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        Supplier::create($request->all());

        return redirect()->back()->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function destroy(Inventory $inventory)
    {
        // Check if inventory is used in any menu ingredients
        if ($inventory->menuIngredients()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus bahan ini karena masih digunakan dalam resep menu. Hapus atau ubah resep menu terlebih dahulu.');
        }

        $inventory->delete();
        return redirect()->back()->with('success', 'Bahan baku berhasil dihapus.');
    }
}
