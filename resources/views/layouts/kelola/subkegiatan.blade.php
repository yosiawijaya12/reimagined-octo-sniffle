@extends('layouts.app')

@section('title', 'Kelola Sub Kegiatan')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <h2 class="text-xl font-semibold text-gray-700">Daftar Sub Kegiatan</h2>
        <div class="flex gap-4 items-center">
            <div class="relative w-full md:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="live-search" name="search" value="{{ request('search') }}" placeholder="Cari Sub Kegiatan..." class="form-input w-full pl-10 pr-4 py-2 rounded-xl border border-gray-300 bg-white text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300 transition"/>
            </div>

            <button onclick="openSubModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition">
                <i class="fas fa-plus mr-2"></i>Tambah Sub Kegiatan
            </button>
        </div>
    </div>

    <!-- Form Filter -->
    <form method="GET" class="mb-4 bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Filter Sub Kegiatan</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <!-- Kegiatan -->
            <div>
                <label for="id_kegiatan" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-building mr-1 text-gray-500"></i> Kegiatan
                </label>
                <select name="id_kegiatan" id="id_kegiatan" class="form-select w-full mt-1">
                    <option value="">-- Semua Kegiatan --</option>
                    @foreach ($kegiatan as $k)
                        <option value="{{ $k->id }}" {{ request('id_kegiatan') == $k->id ? 'selected' : '' }}>{{ $k->nama_kegiatan }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tahun Anggaran -->
            <div>
                <label for="tahun_anggaran" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-calendar-alt mr-1 text-gray-500"></i> Tahun Anggaran
                </label>
                <input type="text" name="tahun_anggaran" id="tahun_anggaran" value="{{ request('tahun_anggaran') }}" class="form-input w-full mt-1" placeholder="Contoh: 2025">
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
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md transition">
                <i class="fas fa-check mr-1"></i> Terapkan
            </button>
            <a href="{{ route('subkegiatan.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg shadow-md transition">
                <i class="fas fa-times mr-1"></i> Reset
            </a>
        </div>
    </form>

    <!-- Tabel -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blue-50">
                <tr class="text-gray-700 uppercase text-xs font-bold tracking-wider">
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Kegiatan</th>
                    <th class="px-6 py-4">Sub Kegiatan</th>
                    <th class="px-6 py-4">Tahun Anggaran</th>
                    <th class="px-6 py-4">Rekening</th>
                    <th class="px-6 py-4">Jumlah Pagu</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach ($subkegiatan as $index => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">{{ $index + 1 + ($subkegiatan->currentPage() - 1) * $subkegiatan->perPage() }}</td>
                        <td class="px-6 py-4">{{ $item->kegiatan->nama_kegiatan ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $item->nama_subkegiatan }}</td>
                        <td class="px-6 py-4">{{ $item->tahun_anggaran }}</td>
                        <td class="px-6 py-4">{{ $item->rekening }}</td>
                        <td class="px-6 py-4">Rp{{ number_format($item->jumlah_pagu, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center flex gap-2">
                            <button class="text-yellow-500 hover:text-yellow-700" onclick='editSub(@json($item))'>
                                <i class="fas fa-edit fa-lg"></i>
                            </button>
                            <form action="{{ route('subkegiatan.destroy', $item->id) }}" method="POST" class="inline" id="delete-form-{{ $item->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="text-red-500 hover:text-red-700" onclick="confirmDelete({{ $item->id }})">
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
        {{ $subkegiatan->appends(request()->query())->links() }}
    </div>

    @include('layouts.kelola.subkegiatan_form')
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
    function openSubModal() {
        const modal = document.getElementById('modalSubForm');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.getElementById('subForm').reset();
        document.getElementById('subForm').action = "{{ route('subkegiatan.store') }}";
        document.getElementById('subkegiatan-id').value = '';
    }

    function closeSubModal() {
        const modal = document.getElementById('modalSubForm');
        modal.classList.add('hidden');
    }

    function editSub(data) {
        openSubModal();
        document.getElementById('subForm').action = `/subkegiatan/update/${data.id}`;
        document.getElementById('subkegiatan-id').value = data.id;
        document.getElementById('id_kegiatan').value = data.id_kegiatan;
        document.getElementById('nama_subkegiatan').value = data.nama_subkegiatan;
        document.getElementById('tahun_anggaran').value = data.tahun_anggaran;
        document.getElementById('rekening').value = data.rekening;
        document.getElementById('jumlah_pagu').value = data.jumlah_pagu;
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: "Apakah Anda yakin ingin menghapus Sub Kegiatan ini?",
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

