@extends('layouts.app')
@section('title', 'Pelaporan Masuk')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <div class="w-full md:w-auto mb-4 md:mb-0">
            <!-- Search Input -->
            <div class="relative w-full md:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="live-search" name="search" value="{{ request('search') }}"
                    placeholder="Cari kegiatan..."
                    class="form-input w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:ring focus:ring-blue-200 transition" />
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-4 mb-6">
        <form method="GET" action="{{ route('pelaporan.masuk') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- PPTK -->
                <div>
                    <label for="pptk" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-user mr-1 text-gray-500"></i> PPTK
                    </label>
                    <select name="pptk" id="pptk" class="form-select w-full mt-1">
                        <option value="">-- Semua --</option>
                        @php
                            $pptkMapping = [
                                2 => 'IKP',
                                3 => 'TKI',
                                4 => 'Sekretariat',
                            ];
                        @endphp
                        @foreach ($pptkMapping as $id => $nama)
                            <option value="{{ $id }}" {{ request('pptk') == $id ? 'selected' : '' }}>
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tahun -->
                <div>
                    <label for="tahun" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-calendar-alt mr-1 text-gray-500"></i> Tahun
                    </label>
                    <select name="tahun" id="tahun" class="form-select w-full mt-1">
                        @for ($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-sort mr-1 text-gray-500"></i> Urutkan
                    </label>
                    <select name="sort" class="form-select w-full mt-1">
                        <option value="created_desc" {{ request('sort') == 'created_desc' ? 'selected' : '' }}>
                            Terbaru</option>
                        <option value="created_asc" {{ request('sort') == 'created_asc' ? 'selected' : '' }}>
                            Terlama</option>
                        <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>Nama A-Z
                        </option>
                        <option value="nama_desc" {{ request('sort') == 'nama_desc' ? 'selected' : '' }}>Nama Z-A
                        </option>
                    </select>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-2 mt-4">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md transition">
                    <i class="fas fa-check mr-1"></i> Terapkan
                </button>
            </div>
        </form>
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
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach ($laporan as $data)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            {{ $loop->iteration + ($laporan->currentPage() - 1) * $laporan->perPage() }}
                        </td>
                        <td class="px-6 py-4 uppercase">
                            {{ $data->pptk ? ($pptkMapping[$data->pptk_id] ?? '-') : '-' }}
                        </td>
                        <td class="px-6 py-4">{{ $data->jenis_belanja }}</td>
                        <td class="px-6 py-4">
                            {{ $data->kegiatan ? $data->kegiatan->nama_kegiatan : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $data->subkegiatan ? $data->subkegiatan->nama_subkegiatan : '-' }}
                        </td>
                        <td class="px-6 py-4">{{ $data->rekening_kegiatan }}</td>
                        <td class="px-6 py-4">{{ $data->periode }}</td>
                        <td class="px-6 py-4">Rp{{ number_format($data->nominal_pagu, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">Rp{{ number_format($data->nominal, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @if ($data->file_path)
                                <a href="{{ asset('storage/' . $data->file_path) }}" target="_blank"
                                    class="text-blue-600 hover:text-blue-800 flex items-center justify-center">
                                    <i class="fas fa-file-pdf fa-lg text-red-500"></i>
                                </a>
                            @else
                                <span class="text-gray-400 italic">Tidak ada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $data->status }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button class="text-yellow-500 hover:text-yellow-700"
                                onclick='verifikasiLaporan(@json($data))'>
                                <i class="fas fa-edit fa-lg"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $laporan->appends(request()->query())->links() }}
    </div>
</div>
@endsection

@include('layouts.verifikasi.verifikasi')

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
</script>
@endpush
