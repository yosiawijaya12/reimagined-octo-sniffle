<?php

namespace App\Http\Controllers;

use App\Models\SubKegiatan;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $query = SubKegiatan::with('kegiatan');

        // Filter berdasarkan kegiatan jika ada
        if ($kegiatanId = $request->input('id_kegiatan')) {
            $query->where('id_kegiatan', $kegiatanId);
        }

        // Filter berdasarkan tahun anggaran jika ada
        if ($tahunAnggaran = $request->input('tahun_anggaran')) {
            $query->where('tahun_anggaran', $tahunAnggaran);
        }

        // Search functionality
        if ($search = $request->input('search')) {
            $query->where('nama_subkegiatan', 'like', '%' . $search . '%');
        }

        // Sorting
        $sort = $request->input('sort', 'created_desc'); // Default sort by created_at desc
        switch ($sort) {
            case 'created_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'nama_asc':
                $query->orderBy('nama_subkegiatan', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('nama_subkegiatan', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $subkegiatan = $query->paginate(10);
        $kegiatan = Kegiatan::all();

        return view('layouts.kelola.subkegiatan', compact('subkegiatan', 'kegiatan'));
    }


    public function store(Request $request)
    {
        try {
            SubKegiatan::create($request->all());
            return redirect()->back()->with('success', 'Sub Kegiatan ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan Sub Kegiatan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = SubKegiatan::findOrFail($id);
            $data->update($request->all());
            return redirect()->back()->with('success', 'Sub Kegiatan diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui Sub Kegiatan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            SubKegiatan::findOrFail($id)->delete();
            return redirect()->back()->with('success', 'Sub Kegiatan dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus Sub Kegiatan: ' . $e->getMessage());
        }
    }
}