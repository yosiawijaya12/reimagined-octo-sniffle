<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\SubKegiatan;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getKegiatan($pptk)
    {
        $mapping = [
            '4' => 'sekretariat',
            '2' => 'ikp',
            '3' => 'tki',
        ];

        if (!isset($mapping[$pptk])) {
            return response()->json([], 404);
        }

        $satuanKerja = $mapping[$pptk];
        $kegiatan = Kegiatan::where('satuan_kerja', $satuanKerja)->get();

        return response()->json($kegiatan);
    }

    public function getSubKegiatan($kegiatanId)
    {
        $subKegiatan = SubKegiatan::where('id_kegiatan', $kegiatanId)->get();
        return response()->json($subKegiatan);
    }

    public function getSubKegiatanDetail($subkegiatanId)
    {
        $detail = SubKegiatan::find($subkegiatanId);
        return response()->json($detail);
    }
}