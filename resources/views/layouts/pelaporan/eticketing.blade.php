@extends('layouts.app')

@section('title', 'E-Ticketing Laporan Disetujui')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Laporan Disetujui Kepala Dinas</h1>

    <div class="overflow-x-auto bg-white rounded-lg shadow-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blue-50">
                <tr class="text-gray-700 uppercase text-xs font-bold tracking-wider">
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">PPTK</th>
                    <th class="px-6 py-4">Jenis</th>
                    <th class="px-6 py-4">Kegiatan</th>
                    <th class="px-6 py-4">Sub Kegiatan</th>
                    <th class="px-6 py-4">Rekening</th>
                    <th class="px-6 py-4">Nominal Pagu</th> <!-- Kolom baru -->
                    <th class="px-6 py-4">Anggaran</th>
                    <th class="px-6 py-4">File</th> <!-- Kolom baru -->
                    {{-- <th class="px-6 py-4">Aksi</th> --}}
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach ($laporan as $data)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">{{ $loop->iteration + ($laporan->currentPage() - 1) * $laporan->perPage() }}</td>
                    <td class="px-6 py-4 uppercase">{{ $data->pptk?->role ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $data->jenis_belanja }}</td>
                    <td class="px-6 py-4">{{ $data->kegiatan?->nama_kegiatan ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $data->subkegiatan?->nama_subkegiatan ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $data->rekening_kegiatan }}</td>
                    <td class="px-6 py-4">Rp{{ number_format($data->nominal_pagu, 0, ',', '.') }}</td> <!-- Kolom baru -->
                    <td class="px-6 py-4">Rp{{ number_format($data->nominal, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @if ($data->file_path)
                            <a href="{{ asset('storage/' . $data->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-file-pdf fa-lg"></i>
                            </a>
                        @else
                            <span class="text-gray-400 italic">Tidak ada</span>
                        @endif
                    </td> <!-- Kolom baru -->
                    {{-- <td class="px-6 py-4 text-center">
                        <button onclick="showDetail({{ $data->id }})" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-eye fa-lg"></i>
                        </button>
                    </td> --}}
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $laporan->links() }}
    </div>
</div>

<!-- Modal -->
<div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-6">
        <h2 class="text-lg font-semibold mb-4">Detail Laporan</h2>
        <div id="modalContent">
            <p>Memuat data...</p>
        </div>
        <div class="text-right mt-4">
            <button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showDetail(id) {
        fetch(`/e-ticketing/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('modalContent').innerHTML = `
                    <p><strong>PPTK:</strong> ${data.pptk?.role ?? '-'}</p>
                    <p><strong>Jenis Belanja:</strong> ${data.jenis_belanja}</p>
                    <p><strong>Kegiatan:</strong> ${data.kegiatan?.nama_kegiatan ?? '-'}</p>
                    <p><strong>Sub Kegiatan:</strong> ${data.subkegiatan?.nama_subkegiatan ?? '-'}</p>
                    <p><strong>Rekening:</strong> ${data.rekening_kegiatan}</p>
                    <p><strong>Nominal Pagu:</strong> Rp${Number(data.nominal_pagu).toLocaleString('id-ID')}</p> <!-- Menampilkan Nominal Pagu -->
                    <p><strong>Anggaran:</strong> Rp${Number(data.nominal).toLocaleString('id-ID')}</p>
                    <p><strong>Catatan:</strong> ${data.catatan ?? '-'}</p>
                    <p><strong>Periode:</strong> ${data.periode}</p>
                    <p><strong>File:</strong> ${data.file_path ? `<a class="text-blue-600 underline" href="/storage/${data.file_path}" target="_blank">Lihat File</a>` : 'Tidak ada file'}</p>
                `;
                document.getElementById('detailModal').classList.remove('hidden');
                document.getElementById('detailModal').classList.add('flex');
            });
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }
</script>
@endpush
