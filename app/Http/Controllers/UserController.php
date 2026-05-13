<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->whereHas('role', function($q) {
            $q->where('name', 'Kasir');
        })->get();
        
        return view('owner.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        $kasirRole = Role::where('name', 'Kasir')->first();

        if (!$kasirRole) {
            return redirect()->back()->with('error', 'Role Kasir tidak ditemukan. Pastikan seeder telah dijalankan.');
        }

        $username = explode('@', $request->email)[0];
        // Ensure uniqueness
        if (User::where('username', $username)->exists()) {
            $username = $username . rand(10, 99);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $username,
            'password' => Hash::make($request->password),
            'role_id' => $kasirRole->id,
        ]);

        return redirect()->back()->with('success', 'Akun Kasir berhasil dibuat.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:8',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        return redirect()->back()->with('success', 'Informasi Kasir berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Check if user is owner
        if ($user->role && $user->role->name === 'Owner') {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun Owner.');
        }

        // Check for transactions
        $hasTransactions = Transaction::where('kasir_id', $user->id)->exists();
        if ($hasTransactions) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus kasir ini karena sudah memiliki riwayat transaksi. Gunakan fitur non-aktifkan jika tersedia.');
        }

        try {
            $user->delete();
            return redirect()->back()->with('success', 'Akun Kasir berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus akun: ' . $e->getMessage());
        }
    }
}
