<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pegawai;

class PegawaiSeeder extends Seeder
{
    public function run()
    {
        Pegawai::create([
            'nik' => '3201292303110313',
            'nama' => 'SITI NUR MUTIA ANDINI',
            'jabatan' => 'Tenaga Administrasi',
            'mulai_kerja' => '2016-01-04',
            'selesai_kerja' => '2023',
            'foto' => 'img/siti.jpg',
        ]);

        Pegawai::create([
            'nik' => '3276051006150015',
            'nama' => 'AMBI FERDIANDI',
            'jabatan' => 'Tenaga Administrasi',
            'mulai_kerja' => '2014-01-03',
            'selesai_kerja' => '2023',
            'foto' => 'img/ambi.jpg',
        ]);
    }
}
