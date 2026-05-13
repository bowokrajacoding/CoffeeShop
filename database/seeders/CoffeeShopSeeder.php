<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Inventory;
use App\Models\Menu;
use App\Models\MenuIngredient;
use App\Models\Promo;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Receipt;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CoffeeShopSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $ownerRole = Role::create(['name' => 'Owner']);
        $kasirRole = Role::create(['name' => 'Kasir']);

        // 2. Users
        User::create([
            'name' => 'Owner Kedai',
            'username' => 'owner',
            'email' => 'owner@kopi.com',
            'password' => Hash::make('password'),
            'role_id' => $ownerRole->id,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Kasir Budi',
            'username' => 'budi',
            'email' => 'budi@kopi.com',
            'password' => Hash::make('password'),
            'role_id' => $kasirRole->id,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Kasir Ani',
            'username' => 'ani',
            'email' => 'ani@kopi.com',
            'password' => Hash::make('password'),
            'role_id' => $kasirRole->id,
            'is_active' => true,
        ]);

        // 3. Categories
        $categories = [
            ['name' => 'Kopi', 'icon' => 'coffee'],
            ['name' => 'Non-Kopi', 'icon' => 'glass-water'],
            ['name' => 'Snack', 'icon' => 'cookie'],
            ['name' => 'Makanan Berat', 'icon' => 'utensils'],
            ['name' => 'Tambahan', 'icon' => 'plus'],
        ];
        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // 4. Suppliers
        $suppliers = [
            ['name' => 'PT Kopi Jaya', 'contact' => '08123456789', 'address' => 'Bandung'],
            ['name' => 'Susu Segar Farm', 'contact' => '08123456788', 'address' => 'Lembang'],
            ['name' => 'Grosir Sembako', 'contact' => '08123456787', 'address' => 'Jakarta'],
        ];
        foreach ($suppliers as $sup) {
            Supplier::create($sup);
        }

        // 5. Inventory
        $inventoryItems = [
            ['name' => 'Biji Kopi Arabica', 'unit' => 'gram', 'stock_qty' => 5000, 'min_threshold' => 1000, 'supplier_id' => 1],
            ['name' => 'Biji Kopi Robusta', 'unit' => 'gram', 'stock_qty' => 5000, 'min_threshold' => 1000, 'supplier_id' => 1],
            ['name' => 'Susu UHT', 'unit' => 'ml', 'stock_qty' => 10000, 'min_threshold' => 2000, 'supplier_id' => 2],
            ['name' => 'Gula Cair', 'unit' => 'ml', 'stock_qty' => 3000, 'min_threshold' => 500, 'supplier_id' => 3],
            ['name' => 'Bubuk Cokelat', 'unit' => 'gram', 'stock_qty' => 2000, 'min_threshold' => 500, 'supplier_id' => 3],
            ['name' => 'Teh Celup', 'unit' => 'pcs', 'stock_qty' => 100, 'min_threshold' => 20, 'supplier_id' => 3],
            ['name' => 'Kentang Frozen', 'unit' => 'gram', 'stock_qty' => 10000, 'min_threshold' => 2000, 'supplier_id' => 3],
        ];
        foreach ($inventoryItems as $item) {
            Inventory::create($item);
        }

        // 6. Menus
        $menus = [
            ['category_id' => 1, 'name' => 'Espresso', 'price' => 15000, 'image' => 'https://placehold.co/400x400?text=Espresso'],
            ['category_id' => 1, 'name' => 'Americano', 'price' => 18000, 'image' => 'https://placehold.co/400x400?text=Americano'],
            ['category_id' => 1, 'name' => 'Caffe Latte', 'price' => 25000, 'image' => 'https://placehold.co/400x400?text=Latte'],
            ['category_id' => 1, 'name' => 'Cappuccino', 'price' => 25000, 'image' => 'https://placehold.co/400x400?text=Cappuccino'],
            ['category_id' => 1, 'name' => 'Mochaccino', 'price' => 28000, 'image' => 'https://placehold.co/400x400?text=Mochaccino'],
            ['category_id' => 2, 'name' => 'Chocolate Hot', 'price' => 22000, 'image' => 'https://placehold.co/400x400?text=Chocolate'],
            ['category_id' => 2, 'name' => 'Matcha Latte', 'price' => 25000, 'image' => 'https://placehold.co/400x400?text=Matcha'],
            ['category_id' => 2, 'name' => 'Lemon Tea', 'price' => 18000, 'image' => 'https://placehold.co/400x400?text=Lemon+Tea'],
            ['category_id' => 3, 'name' => 'French Fries', 'price' => 20000, 'image' => 'https://placehold.co/400x400?text=Fries'],
            ['category_id' => 3, 'name' => 'Croissant', 'price' => 18000, 'image' => 'https://placehold.co/400x400?text=Croissant'],
        ];
        foreach ($menus as $m) {
            Menu::create($m);
        }

        // 7. Menu Ingredients
        // Espresso uses 18g coffee
        MenuIngredient::create(['menu_id' => 1, 'inventory_id' => 1, 'qty_per_serving' => 18]);
        // Latte uses 18g coffee + 200ml milk
        MenuIngredient::create(['menu_id' => 3, 'inventory_id' => 1, 'qty_per_serving' => 18]);
        MenuIngredient::create(['menu_id' => 3, 'inventory_id' => 3, 'qty_per_serving' => 200]);

        // 8. Promos
        Promo::create([
            'code' => 'KOPIHEMAT',
            'type' => 'percentage',
            'value' => 10,
            'min_order' => 50000,
            'valid_from' => now()->subDays(30),
            'valid_until' => now()->addDays(30),
            'is_active' => true,
        ]);

        Promo::create([
            'code' => 'DISKON5K',
            'type' => 'fixed',
            'value' => 5000,
            'min_order' => 30000,
            'valid_from' => now()->subDays(30),
            'valid_until' => now()->addDays(30),
            'is_active' => true,
        ]);

        // 9. Sample Transactions (30 days)
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $numTransactions = rand(5, 15);

            for ($j = 0; $j < $numTransactions; $j++) {
                $subtotal = rand(30, 100) * 1000;
                $discount = 0;
                $grandTotal = $subtotal;

                $transaction = Transaction::create([
                    'kasir_id' => rand(2, 3),
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'grand_total' => $grandTotal,
                    'payment_method' => ['cash', 'qris', 'debit'][rand(0, 2)],
                    'amount_paid' => $grandTotal + (rand(0, 5) * 5000),
                    'change' => 0,
                    'status' => 'completed',
                    'created_at' => $date->copy()->addHours(rand(8, 20))->addMinutes(rand(0, 59)),
                ]);

                $transaction->change = $transaction->amount_paid - $transaction->grand_total;
                $transaction->save();

                // Add random items
                $numItems = rand(1, 3);
                for ($k = 0; $k < $numItems; $k++) {
                    $menu = Menu::all()->random();
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'menu_id' => $menu->id,
                        'qty' => rand(1, 2),
                        'unit_price' => $menu->price,
                        'subtotal' => $menu->price * rand(1, 2),
                    ]);
                }

                // Create Receipt
                Receipt::create([
                    'transaction_id' => $transaction->id,
                    'receipt_number' => 'TRX-' . $transaction->created_at->format('Ymd') . '-' . str_pad($transaction->id, 4, '0', STR_PAD_LEFT),
                    'printed_at' => $transaction->created_at,
                ]);
            }
        }
    }
}
