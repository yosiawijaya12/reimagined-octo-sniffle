<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelaporan extends Model
{
    use HasFactory;

    protected $table = 'pelaporan';
    
    protected $fillable = [
        'pptk_id',
        'kegiatan_id',
        'subkegiatan_id',
        'jenis_belanja',
        'rekening_kegiatan',
        'periode',
        'nominal_pagu',
        'nominal',
        'status',
        'file_path',
        'catatan',
    ];

    // Laporan.php
    public function pptk()
    {
        return $this->belongsTo(User::class, 'pptk_id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function subkegiatan()
    {
        return $this->belongsTo(SubKegiatan::class, 'subkegiatan_id');
    }

}

