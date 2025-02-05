<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelatihanController extends Controller
{
    public function index()
    {
        $pelatihan = Pelatihan::where('pegawai_id', Auth::user()->id)
            ->latest()
            ->get();
        return view('pelatihan.index', compact('pelatihan'));
    }

    public function create()
    {
        return view('pelatihan.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_pelatihan' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
            'penyelenggara' => 'required|in:dinas,non dinas',
            'nama_penyelenggara' => 'required|string|max:255',
            'kontak_penyelenggara' => 'required|string|max:100',
            'nomor_surat' => 'required|string|max:100',
            'sertifikat' => 'required|mimes:pdf,jpg,png|max:2048', // Validasi sertifikat
        ]);
    
        try {
            // Simpan file sertifikat jika ada
            $sertifikatPath = null;
            if ($request->hasFile('sertifikat')) {
                $sertifikatPath = $request->file('sertifikat')->store('sertifikat_pelatihan', 'public');
            }
    
            // Buat data pelatihan baru
            Pelatihan::create([
                'nama_pelatihan' => $validated['nama_pelatihan'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'lokasi' => $validated['lokasi'],
                'penyelenggara' => $validated['penyelenggara'],
                'nama_penyelenggara' => $validated['nama_penyelenggara'],
                'kontak_penyelenggara' => $validated['kontak_penyelenggara'],
                'nomor_surat' => $validated['nomor_surat'],
                'sertifikat' => $sertifikatPath,
                'pegawai_id' => Auth::user()->id,
                'status' => 'pending'
            ]);
    
            return redirect()->route('pelatihan.index')
                ->with('success', 'Data pelatihan berhasil disimpan dan menunggu verifikasi');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }
    

    // public function show($id)
    // {
    //     $pelatihan = Pelatihan::where('pegawai_id', auth()->id())
    //         ->findOrFail($id);
        
    //     return view('pelatihan.show', compact('pelatihan'));
    // }

    public function show($id)
    {
        $pelatihan = Pelatihan::findOrFail($id);
        return view('pelatihan.show', compact('pelatihan'));
    }

}
