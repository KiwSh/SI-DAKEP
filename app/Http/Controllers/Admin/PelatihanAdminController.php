<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelatihanAdminController extends Controller
{
    public function index()
    {
        // dd('dhsds');
        // $pelatihans = Pelatihan::all();
        $pelatihan = Pelatihan::with('pegawai', 'VerifiedBy')
            ->latest()
            ->get();
        // dd($pelatihan);
        return view('admin.pelatihan.index', compact('pelatihan'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan_admin' => 'required_if:status,rejected'
        ], [
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status harus berupa "approved" atau "rejected".',
            'catatan_admin.required_if' => 'Catatan wajib diisi jika status ditolak.'
        ]);
    
        $pelatihan = Pelatihan::findOrFail($id);
    
        $pelatihan->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
            'verified_at' => now(),
            'verified_by' => Auth::user()->id
        ]);
    
        return redirect()->back()->with('success', 'Verifikasi pelatihan berhasil!');
    }
    

    
}