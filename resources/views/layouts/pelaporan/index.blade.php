@extends('layouts.app')

@section('title', 'Daftar Pelaporan')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <div class="flex gap-4 items-center">
            <!-- Filter Button -->
            <button type="button" onclick="openFilterModal()"
                class="text-blue-600 hover:text-blue-800 px-4 py-2 rounded-md border border-blue-600 flex items-center gap-2">
                <i class="fas fa-filter"></i> Filter
            </button>

            <!-- Search Input -->
            <input type="text" id="live-search" name="search" value="{{ request('search') }}"
                placeholder="Cari Kegiatan/Sub Kegiatan..."
                class="form-input w-full md:w-64 px-3 py-2 rounded-lg border-gray-300 focus:ring focus:ring-blue-200 transition" />

            <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition">
                <i class="fas fa-plus mr-2"></i>Tambah Laporan
            </button>
        </div>
    </div>

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
                    <th class="px-6 py-4">Periode</th>
                    <th class="px-6 py-4">Pagu</th>
                    <th class="px-6 py-4">Anggaran</th>
                    <th class="px-6 py-4">File</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Catatan</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach ($laporan as $data)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            {{ $loop->iteration + ($laporan->currentPage() - 1) * $laporan->perPage() }}
                        </td>
                        <td class="px-6 py-4 uppercase">{{ $data->pptk ? $data->pptk->role : '-' }}</td>
                        <td class="px-6 py-4">{{ $data->jenis_belanja }}</td>
                        <td class="px-6 py-4">{{ $data->kegiatan?->nama_kegiatan ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $data->subkegiatan?->nama_subkegiatan ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $data->rekening_kegiatan }}</td>
                        <td class="px-6 py-4">{{ $data->periode }}</td>
                        <td class="px-6 py-4">Rp{{ number_format($data->nominal_pagu, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">Rp{{ number_format($data->nominal, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @if ($data->file_path)
                                <a href="{{ asset('storage/' . $data->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center justify-center">
                                    <i class="fas fa-file-pdf fa-lg text-red-500"></i>
                                </a>
                            @else
                                <span class="text-gray-400 italic">Tidak ada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $data->status }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $data->catatan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center flex gap-2">
                            <button class="text-yellow-500 hover:text-yellow-700" onclick='editLaporan(@json($data))'>
                                <i class="fas fa-edit fa-lg"></i>
                            </button>
                            <form id="deleteForm{{ $data->id }}" action="{{ route('laporan.destroy', $data->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="text-red-500 hover:text-red-700" onclick="confirmDelete({{ $data->id }})">
                                    <i class="fas fa-trash-alt fa-lg"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $laporan->links() }}
    </div>

    <!-- Filter Modal -->
    <div id="filterModal" class="hidden fixed inset-0 bg-black bg-opacity-40 z-50 justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-3xl">
            <h3 class="text-lg font-semibold mb-4">Filter Pelaporan</h3>
            <form method="GET">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <!-- PPTK -->
                    <div>
                        <label for="pptk" class="block text-sm font-medium text-gray-700">PPTK</label>
                        <select name="pptk" id="pptk" class="form-select w-full mt-1">
                            <option value="">-- Semua --</option>
                            <option value="4" {{ request('pptk') == 4 ? 'selected' : '' }}>Sekretariat</option>
                            <option value="2" {{ request('pptk') == 2 ? 'selected' : '' }}>IKP</option>
                            <option value="3" {{ request('pptk') == 3 ? 'selected' : '' }}>TKI</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="form-select w-full mt-1">
                            <option value="">-- Semua --</option>
                            <option value="Diajukan" {{ request('status') == 'Diajukan' ? 'selected' : '' }}>Diajukan</option>
                            <option value="Disetujui Verifikator" {{ request('status') == 'Disetujui Verifikator' ? 'selected' : '' }}>Disetujui Verifikator</option>
                            <option value="Disetujui Bendahara" {{ request('status') == 'Disetujui Bendahara' ? 'selected' : '' }}>Disetujui Bendahara</option>
                            <option value="Disetujui Kepala Dinas" {{ request('status') == 'Disetujui Kepala Dinas' ? 'selected' : '' }}>Disetujui Kepala Dinas</option>
                            <option value="Perlu Revisi" {{ request('status') == 'Perlu Revisi' ? 'selected' : '' }}>Perlu Revisi</option>
                        </select>
                    </div>

                    <!-- Tahun -->
                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                        <select name="tahun" id="tahun" class="form-select w-full mt-1">
                            @for ($i = date('Y'); $i >= 2020; $i--)
                                <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700">Urutkan</label>
                        <select name="sort" class="form-select w-full mt-1">
                            <option value="created_desc" {{ request('sort') == 'created_desc' ? 'selected' : '' }}>Terbaru</option>
                            <option value="created_asc" {{ request('sort') == 'created_asc' ? 'selected' : '' }}>Terlama</option>
                            <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>Nama A-Z</option>
                            <option value="nama_desc" {{ request('sort') == 'nama_desc' ? 'selected' : '' }}>Nama Z-A</option>
                        </select>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeFilterModal()"
                        class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded">Batal</button>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md transition">
                        <i class="fas fa-check mr-1"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @include('layouts.pelaporan._form') 
</div>

@endsection

@push('scripts')
<script>
    function openModal() {
        document.getElementById('modalForm').classList.remove('hidden');
        document.getElementById('modalForm').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('modalForm').classList.add('hidden');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Anda yakin ingin menghapus?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/laporan/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        Swal.fire('Deleted!', 'Pelaporan berhasil dihapus!', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error!', 'Gagal menghapus pelaporan.', 'error');
                    }
                });
            }
        });
    }

    // Live Search
    document.getElementById('live-search').addEventListener('input', function () {
        const params = new URLSearchParams(window.location.search);
        params.set('search', this.value);
        window.history.replaceState({}, '', `${window.location.pathname}?${params}`);
        clearTimeout(this.delay);
        this.delay = setTimeout(() => {
            window.location.href = `${window.location.pathname}?${params}`;
        }, 500);
    });

    function openFilterModal() {
        document.getElementById('filterModal').classList.remove('hidden');
        document.getElementById('filterModal').classList.add('flex');
    }

    function closeFilterModal() {
        document.getElementById('filterModal').classList.add('hidden');
    }
</script>
@endpush