<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jabatan;

class JabatanController extends Controller
{
    // Menampilkan halaman tambah jabatan
    public function index()
    {
        $jabatans = Jabatan::paginate(5); // Ambil semua data jabatan
        return view('jabatan.index', compact('jabatans')); // Kirim ke view
    }

    public function store(Request $request)
{
    $request->validate([
        'nama_jabatan' => 'required|string|max:255',
    ], [
        'nama_jabatan.required' => 'Nama Jabatan tidak boleh kosong.',
        'nama_jabatan.string' => 'Nama Jabatan harus berupa teks.',
    ]);

    // Proses simpan data
    Jabatan::create([
        'nama_jabatan' => $request->nama_jabatan,
    ]);

    return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil ditambahkan!');
}


    // Menghapus data jabatan
    public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();

        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil dihapus.');
    }
}
