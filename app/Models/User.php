<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama_user',  // Menambahkan nama_user
        'username',   // Menambahkan username
        'password',   // Menambahkan password
        'role',       // Menambahkan role
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function boot()
    {
    parent::boot();

    static::creating(function ($user) {
        $user->username = static::generateUniqueUsername($user->name);
    });
    }

    public function pegawai()
    {
        return $this->belongsTo(User::class, 'pegawai_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function pelatihan()
{
    return $this->hasMany(Pelatihan::class, 'pegawai_id'); // Sesuaikan dengan foreign key yang digunakan
}


public static function generateUniqueUsername($namaUser) {
    // Hapus spasi dan karakter khusus
    $baseUsername = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $namaUser));

    // Jika base username kosong, gunakan 'user' sebagai default
    if (empty($baseUsername)) {
        $baseUsername = 'user';
    }

    // Potong menjadi maksimal 20 karakter
    $baseUsername = substr($baseUsername, 0, 20);

    $username = $baseUsername;
    $counter = 1;

    // Cek keunikan username
    while (self::where('username', $username)->exists()) {
        // Jika username sudah ada, tambahkan angka di belakang
        $username = $baseUsername . $counter;
        $counter++;

        // Pastikan panjang username tidak melebihi 50 karakter
        if (strlen($username) > 50) {
            $username = substr($baseUsername, 0, 20 - strlen((string)$counter)) . $counter;
        }
    }

    return $username;
}

    // Menggunakan kolom 'username' sebagai pengganti 'email' untuk autentikasi
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function hasRole($role)
    {
        return $this->role === $role; // Assuming you have a 'role' column in your users table
    }

    public function pegawaiS () {
        return $this->belongsTo(Pegawai::class);
    }
    
}
