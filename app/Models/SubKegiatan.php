<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKegiatan extends Model
{
    use HasFactory;

    protected $table = 'subkegiatan'; 

    protected $fillable = [
        'id_kegiatan',
        'nama_subkegiatan',
        'tahun_anggaran',
        'rekening',
        'jumlah_pagu'
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
    }
}
