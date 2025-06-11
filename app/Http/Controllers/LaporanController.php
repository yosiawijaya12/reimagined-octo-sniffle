<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\VerifikasiLaporan;
use App\Models\Pelaporan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{

    public function verifikasi(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $laporan = Pelaporan::findOrFail($request->id);

        VerifikasiLaporan::create([
            'dpa_skpd_id' => $laporan->id,
            'verifikator_id' => $user->id,
            'tanggal_verifikasi' => now(),
            'catatan' => '-',
            'status' => 'Disetujui',
        ]);

        if ($user->role === 'verifikator') {
            $laporan->status = 'Disetujui Verifikator';
        } elseif ($user->role === 'bendahara') {
            $laporan->status = 'Disetujui Bendahara';
        } elseif ($user->role === 'kepala_dinas') {
            $laporan->status = 'Disetujui Kepala Dinas';
        }

        $laporan->save();

        return redirect()->back()->with('success', 'Laporan berhasil diverifikasi.');
    }

    public function revisi(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => 'required|exists:pelaporan,id',
            'catatan' => 'required|string',
        ]);

        $user = Auth::user();
        $laporan = Pelaporan::findOrFail($request->id);

        VerifikasiLaporan::create([
            'dpa_skpd_id' => $laporan->id,
            'verifikator_id' => $user->id,
            'tanggal_verifikasi' => now(),
            'catatan' => $request->catatan,
            'status' => 'Revisi',
        ]);

        $laporan->status = 'Perlu Revisi';
        $laporan->catatan = $request->catatan;
        $laporan->save();

        return redirect()->back()->with('success', 'Laporan dikembalikan untuk revisi.');
    }

    public function handleVerifikasiRevisi(Request $request): RedirectResponse
    {
        $action = $request->input('action');
        if ($action === 'verifikasi') {
            return $this->verifikasi($request);
        } elseif ($action === 'revisi') {
            return $this->revisi($request);
        }

        return redirect()->back()->with('error', 'Aksi tidak valid.');
    }

}