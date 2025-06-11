<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kegiatan::query();

        // Filter berdasarkan satuan kerja jika ada
        if ($satuanKerja = $request->input('satuan_kerja')) {
            $query->where('satuan_kerja', $satuanKerja);
        }

        // Filter berdasarkan tahun jika ada
        if ($tahun = $request->input('tahun')) {
            $query->where('tahun', $tahun);
        }

        // Search functionality
        if ($search = $request->input('search')) {
            $query->where('nama_kegiatan', 'like', '%' . $search . '%');
        }

        // Sorting
        $sort = $request->input('sort', 'created_desc'); // Default sort by created_at desc
        switch ($sort) {
            case 'created_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'nama_asc':
                $query->orderBy('nama_kegiatan', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('nama_kegiatan', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $kegiatan = $query->paginate(10);
        return view('layouts.kelola.kegiatan', compact('kegiatan'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'satuan_kerja' => 'required|in:ikp,tki,sekretariat',
            'nama_kegiatan' => 'required|string|max:255',
            'tahun' => 'required|integer',
        ]);


        try {
            if ($request->id) {
                Kegiatan::findOrFail($request->id)->update($validated);
                session()->flash('success', 'Kegiatan berhasil diperbarui.');
            } else {
                Kegiatan::create($validated);
                session()->flash('success', 'Kegiatan berhasil disimpan.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan kegiatan: ' . $e->getMessage());
        }

        return redirect()->route('kegiatan.index');
    }

    public function destroy($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->delete();

        return redirect()->route('kegiatan.index');
    }
}
