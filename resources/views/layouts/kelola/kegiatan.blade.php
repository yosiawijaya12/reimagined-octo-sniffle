@extends('layouts.app')

@section('title', 'Data Kegiatan')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <!-- Heading dan Search & Tambah Kegiatan -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <h2 class="text-xl font-semibold text-gray-700">Daftar Kegiatan</h2>
        <div class="flex flex-col md:flex-row md:items-center gap-2 w-full md:w-auto">
            <!-- Search Input -->
            <div class="relative w-full md:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="live-search" name="search" value="{{ request('search') }}"
                    placeholder="Cari kegiatan..."
                    class="form-input w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:ring focus:ring-blue-200 transition" />
            </div>

            <!-- Tambah Button -->
            <button onclick="openKegiatanModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition">
                <i class="fas fa-plus mr-2"></i>Tambah Kegiatan
            </button>
        </div>
    </div>

    <!-- Form Filter -->
    <form method="GET" class="mb-4 bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Filter Kegiatan</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <!-- Satuan Kerja -->
            <div>
                <label for="satuan_kerja" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-building mr-1 text-gray-500"></i> Satuan Kerja
                </label>
                <select name="satuan_kerja" id="satuan_kerja" class="form-select w-full mt-1">
                    <option value="">-- Semua --</option>
                    <option value="ikp" {{ request('satuan_kerja') == 'ikp' ? 'selected' : '' }}>IKP</option>
                    <option value="tki" {{ request('satuan_kerja') == 'tki' ? 'selected' : '' }}>TKI</option>
                    <option value="sekretariat" {{ request('satuan_kerja') == 'sekretariat' ? 'selected' : '' }}>Sekretariat</option>
                </select>
            </div>

            <!-- Tahun -->
            <div>
                <label for="tahun" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-calendar-alt mr-1 text-gray-500"></i> Tahun
                </label>
                <select name="tahun" id="tahun" class="form-select w-full mt-1">
                    @for ($i = date('Y'); $i >= 2020; $i--)
                        <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <!-- Sort -->
            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-sort mr-1 text-gray-500"></i> Urutkan
                </label>
                <select name="sort" id="sort" class="form-select w-full mt-1">
                    <option value="created_desc" {{ request('sort') == 'created_desc' ? 'selected' : '' }}>Terbaru</option>
                    <option value="created_asc" {{ request('sort') == 'created_asc' ? 'selected' : '' }}>Terlama</option>
                    <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>Nama A-Z</option>
                    <option value="nama_desc" {{ request('sort') == 'nama_desc' ? 'selected' : '' }}>Nama Z-A</option>
                </select>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-2">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md transition">
                <i class="fas fa-check mr-1"></i> Terapkan
            </button>
             <a href="{{ route('kegiatan.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg shadow-md transition">
                <i class="fas fa-times mr-1"></i> Reset
            </a>
        </div>
    </form>

    <!-- Tabel -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blue-50">
                <tr class="text-gray-700 uppercase text-xs font-bold tracking-wider">
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Satuan Kerja</th>
                    <th class="px-6 py-3">Nama Kegiatan</th>
                    <th class="px-6 py-3">Tahun</th>
                    <th class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($kegiatan as $data)
                <tr>
                    <td class="px-6 py-4">{{ $loop->iteration + ($kegiatan->currentPage() - 1) * $kegiatan->perPage() }}</td>
                    <td class="px-6 py-4 uppercase">{{ $data->satuan_kerja }}</td>
                    <td class="px-6 py-4">{{ $data->nama_kegiatan }}</td>
                    <td class="px-6 py-4">{{ $data->tahun }}</td>
                    <td class="px-6 py-4 flex gap-2">
                        <button onclick='editKegiatan(@json($data))' class="text-yellow-500 hover:text-yellow-700">
                            <i class="fas fa-edit fa-lg"></i>
                        </button>
                        <form action="{{ route('kegiatan.destroy', $data->id) }}" method="POST" id="delete-form-{{ $data->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete({{ $data->id }})" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash fa-lg"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">Data kegiatan tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $kegiatan->appends(request()->query())->links() }}
    </div>

    @include('layouts.kelola.kegiatan_form')
</div>
@endsection

@push('scripts')
<script>
    // Live Search
    document.getElementById('live-search').addEventListener('input', function () {
        const params = new URLSearchParams(window.location.search);
        params.set('search', this.value);
		params.delete('page'); // Reset pagination to page 1
        window.history.replaceState({}, '', `${window.location.pathname}?${params}`);
        clearTimeout(this.delay);
        this.delay = setTimeout(() => {
            window.location.href = `${window.location.pathname}?${params}`;
        }, 500);
    });

    // Modal control
    function openKegiatanModal() {
        document.getElementById('form-kegiatan').reset();
        document.getElementById('kegiatan-id').value = '';
        document.getElementById('modalKegiatan').classList.remove('hidden');
        document.getElementById('modalKegiatan').classList.add('flex');
    }

    function editKegiatan(data) {
        document.getElementById('kegiatan-id').value = data.id;
        document.getElementById('satuan_kerja').value = data.satuan_kerja;
        document.getElementById('nama_kegiatan').value = data.nama_kegiatan;
        document.getElementById('tahun').value = data.tahun;
        document.getElementById('modalKegiatan').classList.remove('hidden');
        document.getElementById('modalKegiatan').classList.add('flex');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: "Apakah Anda yakin ingin menghapus kegiatan ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush