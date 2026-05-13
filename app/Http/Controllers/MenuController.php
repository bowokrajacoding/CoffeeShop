<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\MenuIngredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('category', 'ingredients.inventory')->get();
        $categories = Category::all();
        $inventories = Inventory::all();
        return view('owner.menu.index', compact('menus', 'categories', 'inventories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('menus'), $filename);
        $imagePath = 'menus/' . $filename;

        $menu = Menu::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'is_active' => $request->has('is_active'),
        ]);

        // Ingredients
        if ($request->has('ingredients')) {
            foreach ($request->ingredients as $invId => $qty) {
                if ($qty > 0) {
                    MenuIngredient::create([
                        'menu_id' => $menu->id,
                        'inventory_id' => $invId,
                        'qty_per_serving' => $qty,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Menu berhasil ditambahkan.');
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $menu->image;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('menus'), $filename);
            $imagePath = 'menus/' . $filename;
        }

        $menu->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'is_active' => $request->has('is_active'),
        ]);

        // Update ingredients (simple clear and recreate for now)
        $menu->ingredients()->delete();
        if ($request->has('ingredients')) {
            foreach ($request->ingredients as $invId => $qty) {
                if ($qty > 0) {
                    MenuIngredient::create([
                        'menu_id' => $menu->id,
                        'inventory_id' => $invId,
                        'qty_per_serving' => $qty,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        try {
            $menu->delete();
            return redirect()->back()->with('success', 'Menu berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus menu: ' . $e->getMessage());
        }
    }
}
