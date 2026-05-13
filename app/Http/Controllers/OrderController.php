<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use App\Models\Promo;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Inventory;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Menu::where('is_active', true);

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $menus = $query->with('category')->get()->map(function($menu) {
            $menu->image = \Illuminate\Support\Str::startsWith($menu->image, ['http://', 'https://']) 
                ? $menu->image 
                : asset($menu->image);
            return $menu;
        });

        $promos = Promo::where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->get();

        return view('kasir.order.index', compact('categories', 'menus', 'promos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'payment_method' => 'required|string',
            'amount_paid' => 'required|numeric',
        ]);

        return DB::transaction(function () use ($request) {
            $subtotal = 0;
            $items = $request->items;

            // Validate stock
            foreach ($items as $item) {
                $menu = Menu::with('ingredients.inventory')->find($item['id']);
                $subtotal += $menu->price * $item['qty'];

                foreach ($menu->ingredients as $ingredient) {
                    $needed = $ingredient->qty_per_serving * $item['qty'];
                    if ($ingredient->inventory->stock_qty < $needed) {
                        return response()->json([
                            'success' => false,
                            'message' => "Stok bahan '{$ingredient->inventory->name}' tidak mencukupi untuk menu '{$menu->name}'."
                        ], 422);
                    }
                }
            }

            $discount = 0;
            if ($request->promo_id) {
                $promo = Promo::find($request->promo_id);
                if ($promo && $subtotal >= $promo->min_order) {
                    if ($promo->type === 'percentage') {
                        $discount = $subtotal * ($promo->value / 100);
                    } else {
                        $discount = $promo->value;
                    }
                    $promo->increment('used_count');
                }
            }

            $grandTotal = $subtotal - $discount;
            $change = $request->amount_paid - $grandTotal;

            $transaction = Transaction::create([
                'kasir_id' => Auth::id(),
                'promo_id' => $request->promo_id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'grand_total' => $grandTotal,
                'payment_method' => $request->payment_method,
                'amount_paid' => $request->amount_paid,
                'change' => $change,
                'status' => 'completed',
            ]);

            foreach ($items as $item) {
                $menu = Menu::find($item['id']);
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'menu_id' => $menu->id,
                    'qty' => $item['qty'],
                    'custom_notes' => $item['notes'] ?? null,
                    'unit_price' => $menu->price,
                    'subtotal' => $menu->price * $item['qty'],
                ]);

                // Reduce inventory
                foreach ($menu->ingredients as $ingredient) {
                    $needed = $ingredient->qty_per_serving * $item['qty'];
                    $ingredient->inventory->decrement('stock_qty', $needed);
                }
            }

            Receipt::create([
                'transaction_id' => $transaction->id,
                'receipt_number' => 'TRX-' . now()->format('Ymd') . '-' . str_pad($transaction->id, 4, '0', STR_PAD_LEFT),
                'printed_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'transaction_id' => $transaction->id,
                'message' => 'Transaksi berhasil!'
            ]);
        });
    }

    public function history()
    {
        $transactions = Transaction::with('kasir')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('kasir.history', compact('transactions'));
    }

    public function receipt(Transaction $transaction)
    {
        $transaction->load(['kasir', 'details.menu', 'receipt', 'promo']);
        return view('kasir.order.receipt', compact('transaction'));
    }
}
