<?php

namespace App\Imports;

use App\Models\Pegawai;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PegawaiImport implements ToModel, WithHeadingRow
{
    private $rowCount = 0;

    public function model(array $row)
    {
        // Konversi serial date menjadi format tanggal
        $tanggal_lahir = $this->convertExcelDate($row['tanggal_lahir']);
        $mulai_kerja = $this->convertExcelDate($row['mulai_kerja']);
        $selesai_kerja = $this->convertExcelDate($row['selesai_kerja'] ?? null);

        // Validasi: Lewati jika NIK kosong
        if (empty($row['nik'])) {
            return null;
        }

        // Proses foto jika ada
        $fotoLink = null;
        if (!empty($row['foto'])) {
            $fotoLink = $this->storePhoto($row['foto']);
        }

        $this->rowCount++;

        return new Pegawai([
            'nik' => $row['nik'],
            'nama' => $row['nama'],
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $row['alamat'],
            'tb_jabatan_id' => $row['tb_jabatan_id'],
            'mulai_kerja' => $mulai_kerja,
            'lama_kerja' => $row['lama_kerja'] ?? null,
            'selesai_kerja' => $selesai_kerja,
            'foto' => $fotoLink,
        ]);
    }

    /**
     * Mengonversi serial date Excel menjadi format Y-m-d.
     */
    private function convertExcelDate($serialDate)
    {
        if (!$serialDate || !is_numeric($serialDate)) {
            return null;
        }

        // Excel base date: 1 Januari 1900
        return Carbon::createFromFormat('Y-m-d', '1900-01-01')
            ->addDays($serialDate - 2) // Kurangi 2 hari karena bug Excel leap year 1900
            ->format('Y-m-d');
    }

    /**
     * Simpan file foto ke storage dan kembalikan link-nya.
     */
    private function storePhoto($fileName)
    {
        $filePath = public_path('uploads/excel_photos/' . $fileName);

        // Cek apakah file ada di lokasi yang disediakan
        if (file_exists($filePath)) {
            // Simpan ke storage/app/public/foto
            $storedPath = Storage::disk('public')->putFileAs('uploads', $filePath, $fileName);

            // Kembalikan link akses foto
            return asset('storage/' . $storedPath);
        }

        return null;
    }

    public function getRowCount()
    {
        return $this->rowCount;
    }
}
