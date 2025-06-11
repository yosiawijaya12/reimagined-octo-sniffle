<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Pelaporan;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PelaporanController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'pptk' => 'required|string',
            'jenis' => 'required|string',
            'kegiatan' => 'required|string',
            'subkegiatan_id' => 'nullable|integer',
            'rekening_kegiatan' => 'nullable|string',
            'periode' => 'required|string',
            'jumlah_pagu' => 'required|numeric',
            'anggaran_sekarang' => 'required|numeric',
            'file-upload' => 'nullable|mimes:pdf|max:2048',
            'catatan' => 'required|string'
        ]);

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('file-upload')) {
            $filePath = $request->file('file-upload')->store('pelaporan_files', 'public');
        }

        // Simpan ke database
        Pelaporan::create([
            'pptk_id' => $request->pptk,
            'kegiatan_id' => $request->kegiatan,
            'subkegiatan_id' => $request->subkegiatan_id,
            'jenis_belanja' => $request->jenis,
            'rekening_kegiatan' => $request->rekening_kegiatan,
            'periode' => $request->periode,
            'nominal_pagu' => $request->jumlah_pagu,
            'nominal' => $request->anggaran_sekarang,
            'status' => 'Diajukan',
            'file_path' => $filePath,
            'catatan' => '-',
        ]);

        return response()->json(['success' => true, 'message' => 'Pelaporan berhasil disimpan!']);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'pptk' => 'required|string',
            'jenis' => 'required|string',
            'kegiatan' => 'required|string',
            'subkegiatan_id' => 'nullable|integer',
            'rekening_kegiatan' => 'nullable|string',
            'periode' => 'required|string',
            'jumlah_pagu' => 'required|numeric',
            'anggaran_sekarang' => 'required|numeric',
            'file-upload' => 'nullable|mimes:pdf|max:2048',
            'catatan' => 'required|string',
        ]);

        $laporan = Pelaporan::findOrFail($id);

        // Handle file upload baru jika ada
        $filePath = $laporan->file_path;
        if ($request->hasFile('file-upload')) {
            $filePath = $request->file('file-upload')->store('pelaporan_files', 'public');
        }

        $laporan->update([
            'pptk_id' => $request->pptk,
            'kegiatan_id' => $request->kegiatan,
            'subkegiatan_id' => $request->subkegiatan_id,
            'jenis_belanja' => $request->jenis,
            'rekening_kegiatan' => $request->rekening_kegiatan,
            'periode' => $request->periode,
            'nominal_pagu' => $request->jumlah_pagu,
            'nominal' => $request->anggaran_sekarang,
            'status' => 'Diajukan',
            'file_path' => $filePath,
            'catatan' => '-',
        ]);

        return response()->json(['success' => true, 'message' => 'Pelaporan berhasil diperbarui!']);
    }

    public function destroy($id): JsonResponse
    {
        $laporan = Pelaporan::findOrFail($id);
        $laporan->delete();
        return response()->json(['success' => true, 'message' => 'Pelaporan berhasil dihapus!']);
    }

    public function index(Request $request): View
    {
        $user = Auth::user();
        $query = Pelaporan::with(['pptk', 'kegiatan', 'subkegiatan']);

        // Filter berdasarkan status jika ada
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Filter berdasarkan tahun jika ada
        if ($tahun = $request->input('tahun')) {
            $query->whereYear('periode', $tahun);
        }

        // Filter berdasarkan PPTK jika user bukan ikp, tki, atau sekretariat
        if (!in_array($user->role, ['ikp', 'tki', 'sekretariat'])) {
            if ($pptk = $request->input('pptk')) {
                $query->where('pptk_id', $pptk);
            }
        }

        // Search functionality
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('pptk', function ($q2) use ($search) {
                    $q2->where('role', 'like', '%' . $search . '%');
                })
                    ->orWhere('jenis_belanja', 'like', '%' . $search . '%')
                    ->orWhereHas('kegiatan', function ($q2) use ($search) {
                        $q2->where('nama_kegiatan', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('subkegiatan', function ($q2) use ($search) {
                        $q2->where('nama_subkegiatan', 'like', '%' . $search . '%');
                    })
                    ->orWhere('rekening_kegiatan', 'like', '%' . $search . '%')
                    ->orWhere('periode', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('catatan', 'like', '%' . $search . '%');
            });
        }

        // Sorting
        $sort = $request->input('sort', 'created_desc'); // Default sort by created_at desc
        switch ($sort) {
            case 'created_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'pptk_asc':
                $query->orderBy(function ($q) {
                    $q->from('users')->select('role')
                        ->whereColumn('users.id', 'pelaporans.pptk_id');
                }, 'asc');
                break;
            case 'pptk_desc':
                $query->orderBy(function ($q) {
                    $q->from('users')->select('role')
                        ->whereColumn('users.id', 'pelaporans.pptk_id');
                }, 'desc');
                break;
            // Add other sorting options as needed (e.g., by kegiatan name, etc.)
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        if (!in_array($user->role, ['admin', 'verifikator', 'bendahara', 'kepala_dinas'])) {
            $query->whereHas('pptk', function ($q) use ($user) {
                $q->where('role', $user->role);
            });
        }

        $laporan = $query->paginate(10);

        return view('layouts.pelaporan.daftar', compact('laporan'));
    }

    public function masuk(Request $request): View
    {
        $user = Auth::user();
        $query = Pelaporan::with(['pptk', 'kegiatan', 'subkegiatan']);

        // Filter berdasarkan status sesuai dengan role pengguna
        if ($user->role === 'verifikator') {
            $query->where('status', 'Diajukan');
        } elseif ($user->role === 'bendahara') {
            $query->where('status', 'Disetujui Verifikator');
        } elseif ($user->role === 'kepala_dinas') {
            $query->where('status', 'Disetujui Bendahara');
        }

        // Filter berdasarkan PPTK jika user bukan ikp, tki, atau sekretariat
        if (!in_array($user->role, ['ikp', 'tki', 'sekretariat'])) {
            if ($pptk = $request->input('pptk')) {
                $query->where('pptk_id', $pptk);
            }
        }

        // Filter berdasarkan tahun jika ada
        if ($tahun = $request->input('tahun')) {
            $query->whereYear('periode', $tahun);
        }

        // Search functionality
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('pptk', function ($q2) use ($search) {
                    $q2->where('role', 'like', '%' . $search . '%');
                })
                    ->orWhere('jenis_belanja', 'like', '%' . $search . '%')
                    ->orWhereHas('kegiatan', function ($q2) use ($search) {
                        $q2->where('nama_kegiatan', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('subkegiatan', function ($q2) use ($search) {
                        $q2->where('nama_subkegiatan', 'like', '%' . $search . '%');
                    })
                    ->orWhere('rekening_kegiatan', 'like', '%' . $search . '%')
                    ->orWhere('periode', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('catatan', 'like', '%' . $search . '%');
            });
        }

        // Sorting
        $sort = $request->input('sort', 'created_desc'); // Default sort by created_at desc
        switch ($sort) {
            case 'created_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'pptk_asc':
                $query->orderBy(function ($q) {
                    $q->from('users')->select('role')
                        ->whereColumn('users.id', 'pelaporans.pptk_id');
                }, 'asc');
                break;
            case 'pptk_desc':
                $query->orderBy(function ($q) {
                    $q->from('users')->select('role')
                        ->whereColumn('users.id', 'pelaporans.pptk_id');
                }, 'desc');
                break;
            // Add other sorting options as needed (e.g., by kegiatan name, etc.)
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $laporan = $query->paginate(10);

        return view('layouts.pelaporan.masuk', compact('laporan'));
    }
}
