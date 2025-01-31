<?php

namespace App\Http\Controllers;

use App\Models\ProfilPerusahaan;
use Illuminate\Http\Request;

class ProfilePerusahaanController extends Controller
{
    // Menampilkan halaman profil perusahaan
    public function index()
    {
        $profilPerusahaan = ProfilPerusahaan::first();
        return view('profile-perusahaan', compact('profilPerusahaan'));

    }
    

    // Menyimpan data profil perusahaan
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'bidang' => 'required|string',
        ]);

        // Update atau simpan data profil
        ProfilPerusahaan::updateOrCreate(
            ['id' => 1],
            [
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'bidang' => $request->bidang,
            ]
        );

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }

   // Menampilkan form pengelolaan profil perusahaan
// Menampilkan form pengelolaan profil perusahaan
public function edit()
{
    $profilPerusahaan = ProfilPerusahaan::first(); // Mengambil data pertama profil perusahaan
    return view('profileperusahaan\edit', compact('profilPerusahaan')); // Pastikan penulisan sesuai
}


    // Menyimpan perubahan profil perusahaan
    public function update(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'bidang' => 'required|string',
        ]);

        $profilPerusahaan = ProfilPerusahaan::first();
        if ($profilPerusahaan) {
            $profilPerusahaan->update($request->all());
        } else {
            ProfilPerusahaan::create($request->all());
        }

        return redirect()->route('profile-perusahaan.edit')->with('success', 'Profil perusahaan berhasil diperbarui!');
    }
    
}
