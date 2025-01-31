<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'nama_user' => 'Admin',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role'  => 'admin',
        ]);
    }
}