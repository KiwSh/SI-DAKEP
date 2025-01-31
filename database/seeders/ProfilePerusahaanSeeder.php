<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilePerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('profile_perusahaans')->insert([
            'nama' => 'DINAS KOMUNIKASI DAN INFORMATIKA',
            'alamat' => 'KAB. BOGOR',
            'bidang' => 'KOMUNIKASI, INFORMATIKA, PERSANDIAN, dan STATISTIK',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
