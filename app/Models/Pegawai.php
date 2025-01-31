<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pegawai extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $table = 'pegawais';

    protected $fillable = [
        'nik',
        'nama',
        'tanggal_lahir',
        'alamat',
        'jabatans_id',
        'mulai_kerja',
        'lama_kerja',
        'selesai_kerja',
        'foto',
    ];
    



    /**
     * Relasi ke model Jabatan (tb_jabatan).
     */
    public function jabatan()
{
    return $this->belongsTo(Jabatan::class, 'jabatans_id');
}

    public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->logOnly(['nik', 'nama', 'tanggal_lahir', 'alamat', 'jabatans_id', 'mulai_kerja', 'lama_kerja', 'selesai_kerja', 'foto'])
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs()
        ->setDescriptionForEvent(fn(string $eventName) => "Data pegawai telah di-{$eventName}");
}
    
}
