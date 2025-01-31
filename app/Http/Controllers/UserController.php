<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;  // Menggunakan model User yang telah dimodifikasi
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();  // Menampilkan semua pengguna
        return view('Pengguna.index', compact('users'));
    }

    public function create()
    {
        return view('Pengguna.create');  // Menampilkan form untuk tambah pengguna
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',  // Menggunakan tabel 'users'
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,user',
        ]);

        // Menyimpan data pengguna baru
        User::create([
            'nama_user' => $request->nama_user,
            'username' => $request->username,
            'password' => Hash::make($request->password),  // Enkripsi password
            'role' => $request->role,
        ]);

        return redirect()->route('Pengguna.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);  // Menemukan pengguna berdasarkan ID
        return view('Pengguna.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);  // Menemukan pengguna berdasarkan ID

        // Validasi input
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,  // Validasi untuk username yang sudah ada
            'role' => 'required|in:admin,user',
        ]);

        // Data yang akan diperbarui
        $userData = [
            'nama_user' => $request->nama_user,
            'username' => $request->username,
            'role' => $request->role,
        ];

        // Jika password diisi, update password
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);  // Update data pengguna

        return redirect()->route('Pengguna.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);  // Menemukan pengguna berdasarkan ID
        $user->delete();  // Menghapus pengguna

        return redirect()->route('Pengguna.index')->with('success', 'User berhasil dihapus');
    }
}
