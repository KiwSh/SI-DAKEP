<?php

namespace App\Http\Controllers;

use App\Exports\PegawaiExport;
use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ExportPegawaiController extends Controller
{
    // Menampilkan halaman untuk memilih filter export
    public function exportPage(Request $request)
    {
        // Mengambil data jabatan untuk filter
        $jabatans = DB::table('jabatans')->get();

        // Membuat query untuk filter data pegawai
        $query = DB::table('pegawais')
            ->leftJoin('jabatans', 'pegawais.jabatans_id', '=', 'jabatans.id')
            ->select('pegawais.*', 'jabatans.nama_jabatan');

        // Filter berdasarkan Nama / NIK
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('pegawais.nama', 'like', '%' . $request->search . '%')
                    ->orWhere('pegawais.nik', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan Jabatan
        if ($request->has('jabatans') && $request->jabatan) {
            $query->where('pegawais.jabatans_id', $request->jabatan);
        }

        // Filter berdasarkan Tanggal Mulai Kerja
        if ($request->has('mulai_kerja') && $request->mulai_kerja) {
            $query->where('pegawais.mulai_kerja', '>=', $request->mulai_kerja);
        }

        // Filter berdasarkan Tanggal Selesai Kerja
        if ($request->has('selesai_kerja') && $request->selesai_kerja) {
            $query->where('pegawais.mulai_kerja', '<=', $request->selesai_kerja);
        }

        // Ambil data pegawai yang sudah difilter dengan pagination
        $pegawais = $query->paginate(10);

        return view('export.page', compact('jabatans', 'pegawais'));
    }


    public function export(Request $request)
    {
        // Mendapatkan filter dari form
        $search = $request->input('search');
        $jabatanId = $request->input('jabatan');
        $mulaiKerja = $request->input('mulai_kerja');
        $selesaiKerja = $request->input('selesai_kerja');

        // Gunakan export custom dengan drawing
        return Excel::download(new PegawaiExport($search, $jabatanId, $mulaiKerja, $selesaiKerja), 'data_pegawai.xlsx');
    }

    // Metode baru untuk menambahkan drawing ke Excel
    public function addDrawingToExcel($spreadsheet, $pegawai)
    {
        $worksheet = $spreadsheet->getActiveSheet();
        
        // Pastikan path foto valid
        $fotoPath = storage_path('app/public/' . $pegawai->foto);
        
        if (file_exists($fotoPath)) {
            $drawing = new Drawing();
            $drawing->setName($pegawai->nama);
            $drawing->setDescription('Foto Pegawai');
            $drawing->setPath($fotoPath);
            $drawing->setCoordinates('E2'); // Misalnya di kolom E baris 2
            $drawing->setWorksheet($worksheet);
        }

        return $spreadsheet;
    }
}
