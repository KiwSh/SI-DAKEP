<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelatihan extends Model
{
    protected $table = 'pelatihan';
    
    protected $fillable = [
        'pegawai_id',
        'nama_pelatihan',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'penyelenggara',
        'nama_penyelenggara',
        'kontak_penyelenggara',
        'nomor_surat',
        'sertifikat',
        'status',
        'catatan_admin',
        'verified_at',
        'verified_by'
    ];
    

    protected $dates = ['tanggal_mulai', 'tanggal_selesai', 'verified_at'];

    public function getTanggalMulaiAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function getTanggalSelesaiAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function pegawai() {
        return $this->belongsTo(User::class, 'pegawai_id', 'id');
    }

    public function VerifiedBy () {
        return $this->belongsTo(User::class, 'verified_by', 'id');
    }
    
    
    // public function pegawai () {
    //     return $this->hasMany(User::class, 'id', 'pegawai_id');
    // }
}