@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="p-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Dashboard</h2>

        <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded mb-6 border-l-4 border-yellow-500">
            <strong>Selamat Datang!</strong> Anda telah masuk sebagai <strong>{{ Auth::user()->name }}</strong>.
        </div>

        <!-- Filter Form -->
        <form method="GET" class="mb-6 flex flex-col md:flex-row md:items-end gap-4">
            <!-- Tahun -->
            <div class="relative w-full md:w-1/4">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="fas fa-calendar-alt"></i>
                </span>
                <select name="tahun"
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full focus:ring-blue-500 focus:border-blue-500">
                    @for ($i = date('Y'); $i >= 2020; $i--)
                        <option value="{{ $i }}" {{ request('tahun', $tahun) == $i ? 'selected' : '' }}>
                            {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <!-- PPTK (only for roles allowed) -->
            @if (!in_array(auth()->user()->role, ['sekretariat', 'ikp', 'tki']))
                <div class="relative w-full md:w-1/4">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-user-tie"></i>
                    </span>
                    <select name="pptk_id"
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Semua --</option>
                        <option value="4" {{ request('pptk_id') == 4 ? 'selected' : '' }}>Sekretariat</option>
                        <option value="2" {{ request('pptk_id') == 2 ? 'selected' : '' }}>IKP</option>
                        <option value="3" {{ request('pptk_id') == 3 ? 'selected' : '' }}>TKI</option>
                    </select>
                </div>
            @endif

            <!-- Button -->
            <div>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm shadow-md transition">
                    <i class="fas fa-check mr-1"></i> Terapkan
                </button>
            </div>
        </form>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-green-500">
                <div class="text-sm text-green-600 font-bold">TOTAL PAGU</div>
                <div class="text-2xl font-semibold mt-1">{{ number_format($totalPagu, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-cyan-500">
                <div class="text-sm text-cyan-600 font-bold">SERAPAN</div>
                <div class="text-2xl font-semibold mt-1">{{ number_format($serapan, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-yellow-500">
                <div class="text-sm text-yellow-600 font-bold">SISA PAGU</div>
                <div class="text-2xl font-semibold mt-1">{{ number_format($totalPagu - $serapan, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Notification Messages -->
        <div class="bg-white rounded-xl shadow p-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Pesan Masuk</h2>
            @if (is_array($messages) && count($messages) > 0)
                <div class="space-y-4">
                    @foreach ($messages as $msg)
                        <div
                            class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded shadow relative">
                            <button class="absolute top-2 right-2 text-yellow-800 hover:text-yellow-600"
                                onclick="this.parentElement.remove();">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 8.586L15.657 2.93a1 1 0 111.414 1.414L11.414 10l5.657 5.657a1 1 0 01-1.414 1.414L10 11.414l-5.657 5.657a1 1 0 01-1.414-1.414L8.586 10 2.93 4.343a1 1 0 111.414-1.414L10 8.586z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div class="font-bold">{{ $msg['judul'] }}</div>
                            <div>{{ $msg['message'] }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Tidak ada pesan masuk.</p>
            @endif
        </div>

        <div class="flex flex-col md:flex-row gap-4 mt-6">    
            <!-- Pie Chart Section -->
            <div class="flex-1 bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Diagram Pencapaian</h2>
                <div class="relative h-80">
                    <canvas id="paguChart"></canvas>
                </div>
            </div>      
        </div>

        <div class="flex flex-col md:flex-row gap-4 mt-6">    
            {{-- Diagram Batang (Bar Chart) --}}
            <div class="flex-1 bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Diagram Serapan Dana</h2>
                {!! $barChartHtml !!}
                @foreach($tahunDenganData as $tahun => $adaData)
                    @if(!$adaData)
                        <p class="text-gray-500 mt-2">Tidak ada data untuk tahun {{ $tahun }}</p>
                    @endif
                @endforeach
            </div>       
        </div>

        <!-- Table Serapan -->
        @if (in_array(auth()->user()->role, ['verifikator', 'bendahara', 'kepala_dinas', 'admin']))
            <div class="mt-10 bg-white rounded-lg shadow-lg px-6 py-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Tabel Serapan</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-blue-50">
                            <tr class="text-gray-700 uppercase text-xs font-bold tracking-wider">
                                <th class="px-6 py-4 text-left">No</th>
                                <th class="px-6 py-4 text-left">PPTK</th>
                                <th class="px-6 py-4 text-left">Kegiatan</th>
                                <th class="px-6 py-4 text-left">Pagu</th>
                                <th class="px-6 py-4 text-left">Serapan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($tableData as $index => $data)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 uppercase">{{ $data['pptk'] }}</td>
                                    <td class="px-6 py-4">{{ $data['kegiatan'] }}</td>
                                    <td class="px-6 py-4">Rp{{ number_format($data['pagu'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">Rp{{ number_format($data['serapan'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('paguChart').getContext('2d');

    new Chart(ctx, {
        type: 'pie', // Mengubah jenis grafik menjadi pie
        data: {
            labels: ['Serapan', 'Sisa'],
            datasets: [{
                label: 'Pencapaian',
                data: [{{ $serapan }}, {{ $totalPagu - $serapan }}],
                backgroundColor: ['#10B981', '#FBBF24'],
                borderWidth: 1,
                borderColor: '#000',
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 20,
                        padding: 15,
                        color: '#374151'
                    }
                },
                title: {
                    display: true,
                    text: 'Pencapaian Anggaran {{ $tahun }} ({{ $presentase }}%)',
                    font: {
                        size: 18
                    },
                    color: '#111827',
                    padding: {
                        bottom: 20
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
                            return `${context.label}: ${value}`;
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
