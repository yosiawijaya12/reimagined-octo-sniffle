<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifikasiLaporan extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'verifikasi_laporan';

    protected $fillable = [
        'dpa_skpd_id',
        'verifikator_id',
        'tanggal_verifikasi',
        'catatan',
        'status',
    ];

    protected $dates = ['tanggal_verifikasi'];

    // Relasi ke User (verifikator)
    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }

    // Relasi ke DPA atau Pelaporan
    public function pelaporan()
    {
        return $this->belongsTo(Pelaporan::class, 'dpa_skpd_id');
    }
}
