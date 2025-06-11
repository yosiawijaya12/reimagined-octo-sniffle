<?php

namespace App\Http\Controllers;

use App\Models\SubKegiatan;
use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Pelaporan;
use Illuminate\Support\Facades\Auth;
use Maantje\Charts\Bar\Bars;
use Maantje\Charts\Bar\Segment;
use Maantje\Charts\Bar\StackedBar;
use Maantje\Charts\Chart;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $user = Auth::user();
        $pptkFilter = $request->get('pptk_id');

        if (in_array($user->role, ['sekretariat', 'ikp', 'tki'])) {
            $pptkMap = [
                'sekretariat' => 4,
                'ikp' => 2,
                'tki' => 3,
            ];
            $pptkFilter = $pptkMap[$user->role];
        }

        $totalPagu = SubKegiatan::where('tahun_anggaran', $tahun)->sum('jumlah_pagu');

        $query = Pelaporan::where('status', 'Disetujui Kepala Dinas')
            ->whereYear('periode', $tahun);
        if ($pptkFilter) {
            $query->where('pptk_id', $pptkFilter);
        }
        $serapan = $query->sum('nominal');

        $presentase = $totalPagu > 0 ? round(($serapan / $totalPagu) * 100, 2) : 0;

        $messages = $this->pesanMasuk();

        $paguPerPPTK = [];
        foreach ([4, 2, 3] as $pptkId) {
            $paguPerPPTK[$pptkId] = SubKegiatan::where('tahun_anggaran', $tahun)
                ->whereHas('kegiatan', function ($query) use ($pptkId) {
                    $query->where('satuan_kerja', $pptkId);
                })
                ->sum('jumlah_pagu');
        }

        $pptkMapping = [
            2 => 'IKP',
            3 => 'TKI',
            4 => 'Sekretariat',
        ];

        $satuanKerjaFilter = isset($pptkMapping[$pptkFilter]) ? $pptkMapping[$pptkFilter] : null;

        $tableData = [];
        if (in_array($user->role, ['verifikator', 'bendahara', 'kepala_dinas', 'admin'])) {
            $kegiatanList = Kegiatan::where('tahun', $tahun)
                ->when($satuanKerjaFilter, function ($query) use ($satuanKerjaFilter) {
                    return $query->where('satuan_kerja', $satuanKerjaFilter);
                })
                ->get();

            foreach ($kegiatanList as $kegiatan) {
                $pagu = SubKegiatan::where('id_kegiatan', $kegiatan->id)->sum('jumlah_pagu');
                $serapan = Pelaporan::where('status', 'Disetujui Kepala Dinas')
                    ->where('kegiatan_id', $kegiatan->id)
                    ->sum('nominal');

                $tableData[] = [
                    'pptk' => $kegiatan->satuan_kerja,
                    'kegiatan' => $kegiatan->nama_kegiatan,
                    'pagu' => $pagu,
                    'serapan' => $serapan,
                ];
            }
        }

        // Ambil 3 tahun terakhir dari tahun sekarang
        $currentYear = date('Y');
        $tahunList = [
            $currentYear - 2,
            $currentYear - 1,
            $currentYear,
        ];

        $bars = [];
        $tahunDenganData = []; // Array untuk menandai tahun dengan data

        foreach ($tahunList as $t) {
            $serapanTahun = (float) Pelaporan::where('status', 'Disetujui Kepala Dinas')
                ->whereYear('periode', $t)
                ->sum('nominal');

            $paguTahun = (float) SubKegiatan::where('tahun_anggaran', $t)
                ->sum('jumlah_pagu');

            if ($paguTahun > 0) {
                $bars[] = new StackedBar(
                    name: (string) $t,
                    segments: [
                        new Segment(
                            value: $serapanTahun,
                            color: '#2E3D9B',
                            labelColor: 'white'
                        ),
                        new Segment(
                            value: max(0, $paguTahun - $serapanTahun),
                            color: '#0A1450',
                            labelColor: 'white'
                        ),
                    ]
                );
                $tahunDenganData[$t] = true; // Tandai tahun ini memiliki data
            } else {
                // Jika total = 0, tambahkan segment dummy agar chart tetap valid
                $bars[] = new StackedBar(
                    name: (string) $t,
                    segments: [
                        new Segment(
                            value: 1, // Nilai dummy
                            color: '#e0e0e0',
                            labelColor: 'black'
                        ),
                    ]
                );
                $tahunDenganData[$t] = false; // Tandai tahun ini tidak memiliki data
            }

        }

        $chart = new Chart(
            series: [
                new Bars(bars: $bars),
            ],
        );

        $barChartHtml = $chart->render();

        return view('layouts.dashboard', [
            'totalPagu' => $totalPagu,
            'serapan' => $serapan,
            'presentase' => $presentase,
            'tahun' => $tahun,
            'pptk_id' => $pptkFilter,
            'messages' => $messages,
            'paguPerPPTK' => $paguPerPPTK,
            'tableData' => $tableData,
            'barChartHtml' => $barChartHtml,
            'tahunDenganData' => $tahunDenganData, // Kirim informasi ke view
        ]);
    }

    public function pesanMasuk()
    {
        $user = Auth::user();
        $messages = [];

        if ($user->role === 'verifikator') {
            $laporan = Pelaporan::where('status', 'Diajukan')->get();
            foreach ($laporan as $data) {
                $messages[] = [
                    'message' => 'Pengajuan masuk, segera cek kelengkapan pelaporan!',
                    'judul' => $data->kegiatan->nama_kegiatan ?? 'Kegiatan tidak ditemukan',
                ];
            }
        } elseif ($user->role === 'bendahara') {
            $laporan = Pelaporan::where('status', 'Disetujui Verifikator')->get();
            foreach ($laporan as $data) {
                $messages[] = [
                    'message' => 'Pengajuan masuk, segera cek kelengkapan pelaporan!',
                    'judul' => $data->kegiatan->nama_kegiatan ?? 'Kegiatan tidak ditemukan',
                ];
            }
        } elseif ($user->role === 'kepala_dinas') {
            $laporan = Pelaporan::where('status', 'Disetujui Bendahara')->get();
            foreach ($laporan as $data) {
                $messages[] = [
                    'message' => 'Pengajuan masuk, segera cek kelengkapan pelaporan!',
                    'judul' => $data->kegiatan->nama_kegiatan ?? 'Kegiatan tidak ditemukan',
                ];
            }
        } elseif (in_array($user->role, ['ikp', 'tki', 'sekretariat'])) {
            $mapping = [
                'sekretariat' => 4,
                'ikp' => 2,
                'tki' => 3,
            ];
            $pptkId = $mapping[$user->role] ?? null;
            if ($pptkId) {
                $laporan = Pelaporan::where('pptk_id', $pptkId)
                    ->where('status', 'Perlu Revisi')
                    ->get();
                foreach ($laporan as $data) {
                    $messages[] = [
                        'message' => 'Laporan perlu diperbaiki, segera revisi!',
                        'judul' => $data->kegiatan->nama_kegiatan ?? 'Kegiatan tidak ditemukan',
                    ];
                }
            }
        }

        return $messages;
    }
}
