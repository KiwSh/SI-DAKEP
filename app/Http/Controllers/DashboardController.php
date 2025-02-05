<?php

namespace App\Http\Controllers;

use App\Models\ProfilPerusahaan;
use App\Models\Pegawai; // Model untuk Pegawai
use App\Models\Pelatihan;
use App\Models\User; // Model untuk User
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
{
    // Kode yang sudah ada tetap sama
    $profilPerusahaan = ProfilPerusahaan::first();
    $totalPegawai = Pegawai::count();
    $totalPengguna = User::count();
    $totalPelatihan = Pelatihan::count();
    $pelatihanVerifikasi = Pelatihan::where('status', 'verified')->count();
    $pelatihanBelumVerifikasi = Pelatihan::where('status', 'pending')->count();

    // Tambahkan kode untuk menghitung pegawai per jabatan
    $pegawaiPerJabatan = Pegawai::select('jabatans.nama_jabatan as nama_jabatan')
        ->selectRaw('count(*) as total')
        ->join('jabatans', 'pegawais.jabatans_id', '=', 'jabatans.id')
        ->groupBy('jabatans.id', 'jabatans.nama_jabatan')
        ->get();

    // Data jumlah pelatihan per bulan berdasarkan tanggal_mulai
    $pelatihanPerBulan = Pelatihan::selectRaw('MONTH(tanggal_mulai) as bulan, COUNT(*) as total')
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();

    // Format data untuk Chart.js
    $bulanArray = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $dataPelatihan = array_fill(0, 12, 0); // Inisialisasi array 12 bulan dengan nilai 0

    foreach ($pelatihanPerBulan as $data) {
        $dataPelatihan[$data->bulan - 1] = $data->total;
    }

    // Hitung jumlah pelatihan berdasarkan penyelenggara
    $pelatihanDinas = Pelatihan::where('penyelenggara', 'Dinas')->count();
    $pelatihanNonDinas = Pelatihan::where('penyelenggara', 'Non-Dinas')->count();

    // Hitung persentase
    $totalPelatihan = $pelatihanDinas + $pelatihanNonDinas;
    $persenDinas = $totalPelatihan > 0 ? round(($pelatihanDinas / $totalPelatihan) * 100, 2) : 0;
    $persenNonDinas = $totalPelatihan > 0 ? round(($pelatihanNonDinas / $totalPelatihan) * 100, 2) : 0;

    return view('dashboard', compact(
        'profilPerusahaan', 
        'totalPegawai', 
        'totalPengguna', 
        'totalPelatihan', 
        'pelatihanVerifikasi', 
        'pelatihanBelumVerifikasi',
        'pegawaiPerJabatan',
        'bulanArray', 
        'dataPelatihan',
        'pelatihanDinas',
        'pelatihanNonDinas',
        'persenDinas',
        'persenNonDinas'
    ));
}

    public function profile()
    {
        return view('profile');
    }
}
