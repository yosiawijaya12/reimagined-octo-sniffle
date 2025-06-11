@extends('layouts.app')

@section('title', 'Kelola Akun')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-700">Data Akun</h2>
        <button onclick="openAkunModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md">
            <i class="fas fa-plus mr-2"></i>Tambah Akun
        </button>
    </div>

    {{-- Search & Sort --}}
    <form method="GET" class="mb-6 flex flex-col md:flex-row md:items-center gap-4">
        {{-- Search --}}
        <div class="relative w-full md:w-1/3">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama..."
                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- Sort --}}
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-sort"></i>
            </span>
            <select name="sort" onchange="this.form.submit()" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <option value="created_desc" {{ request('sort') == 'created_desc' ? 'selected' : '' }}>Terbaru</option>
                <option value="created_asc" {{ request('sort') == 'created_asc' ? 'selected' : '' }}>Terlama</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>A-Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Z-A</option>
            </select>
        </div>

        {{-- Terapkan --}}
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm shadow-md transition">
            <i class="fas fa-check mr-1"></i> Terapkan
        </button>
    </form>

    {{-- Table --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blue-50">
                <tr class="text-gray-700 uppercase text-xs font-bold tracking-wider">
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Role</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach ($users as $index => $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td class="px-6 py-4">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">{{ $user->role }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <button class="text-yellow-500 hover:text-yellow-700" onclick='editAkun(@json($user))'>
                                <i class="fas fa-edit fa-lg"></i>
                            </button>
                            <form action="{{ route('akun.destroy', $user->id) }}" method="POST" id="delete-form-{{ $user->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="text-red-600 hover:text-red-800" onclick="confirmDelete({{ $user->id }})">
                                    <i class="fas fa-trash fa-lg"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->appends(request()->query())->links() }}
    </div>

    @include('layouts.kelola.akun_form')
</div>
@endsection

@push('scripts')
<script>
    function openAkunModal() {
        document.getElementById('akun-id').value = '';
        document.getElementById('akunForm').reset();
        document.getElementById('modalAkunForm').classList.remove('hidden');
        document.getElementById('modalAkunForm').classList.add('flex');
    }

    function editAkun(data) {
        openAkunModal();
        document.getElementById('akun-id').value = data.id;
        document.getElementById('name').value = data.name;
        document.getElementById('email').value = data.email;
        document.getElementById('role').value = data.role;
    }

    function closeAkunModal() {
        document.getElementById('modalAkunForm').classList.add('hidden');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: "Apakah Anda yakin ingin menghapus akun ini?",
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
