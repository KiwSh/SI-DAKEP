<?php

namespace App\Http\Controllers;

use App\Models\ProfilPerusahaan;
use App\Models\Pegawai; // Model untuk Pegawai
use App\Models\User; // Model untuk User
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Ambil data profil perusahaan
        $profilPerusahaan = ProfilPerusahaan::first();

        // Ambil jumlah total pegawai
        $totalPegawai = Pegawai::count();

        // Ambil jumlah total pengguna (tanpa status)
        $totalPengguna = User::count();

        // Kirimkan data ke view
        return view('dashboard', compact('profilPerusahaan', 'totalPegawai', 'totalPengguna'));
    }


    public function profile()
    {
        return view('profile');
    }
    
    
}
