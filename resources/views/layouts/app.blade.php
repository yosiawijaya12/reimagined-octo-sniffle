<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('storage/images/logo_biru.png') }}" type="image/x-icon"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Financial Report Assistance')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 font-sans antialiased">
<div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="bg-gradient-to-b from-blue-900 to-blue-800 text-white w-64 flex flex-col shadow-xl">
        <div class="p-6">
            <img src="{{ asset('storage/images/logo_putih_crop.PNG') }}" alt="Logo fira" class="mx-auto h-12">
            <p class="text-blue-200 text-sm text-center mt-1">FINANCIAL REPORT ASSISTANCE</p>
        </div>
        <nav class="flex-grow px-4 space-y-1">
            <a href="/dashboard" class="flex items-center py-3 px-4 rounded-lg hover:bg-blue-700/50 transition">
                <i class="fas fa-tachometer-alt w-5"></i>
                <span class="ml-3">Dashboard</span>
            </a>

            <!-- Pelaporan Dropdown -->
            <div x-data="{ open: false }" class="text-white">
                <button @click="open = !open" class="w-full flex items-center justify-between py-3 px-4 rounded-lg hover:bg-blue-700/50 transition">
                    <div class="flex items-center">
                        <i class="fas fa-file-alt w-5"></i>
                        <span class="ml-3">Pelaporan</span>
                    </div>
                    <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="text-xs"></i>
                </button>
                <div x-show="open" class="ml-8 space-y-1 text-sm">
                    <a href="/pelaporan/daftar" class="block py-1 hover:text-blue-200">Daftar Pelaporan</a>

                    @if(in_array(Auth::user()->role, ['admin', 'verifikator', 'bendahara', 'kepala_dinas']))
                        <a href="/pelaporan/masuk" class="block py-1 hover:text-blue-200">Pelaporan Masuk</a>
                        {{-- <a href="/pelaporan/eticketing" class="block py-1 hover:text-blue-200">E-Ticketing</a> --}}
                    @endif
                </div>
            </div>

            <!-- Kelola Dropdown -->
            <div x-data="{ open: false }" class="text-white">
                <button @click="open = !open" class="w-full flex items-center justify-between py-3 px-4 rounded-lg hover:bg-blue-700/50 transition">
                    <div class="flex items-center">
                        <i class="fas fa-cog w-5"></i>
                        <span class="ml-3">Kelola</span>
                    </div>
                    <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="text-xs"></i>
                </button>
                <div x-show="open" class="ml-8 space-y-1 text-sm">
                    <a href="/kelola/kegiatan" class="block py-1 hover:text-blue-200">Kelola Kegiatan</a>
                    <a href="/kelola/subkegiatan" class="block py-1 hover:text-blue-200">Kelola Sub Kegiatan</a>

                    @if(Auth::user()->role === 'admin')
                        <a href="/kelola/akun" class="block py-1 hover:text-blue-200">Kelola Akun</a>
                    @endif
                </div>
            </div>

        </nav>
        <div class="p-4 text-center text-blue-200 text-sm">
            <p>&copy; 2025 Dinas Komunikasi dan Digital Kabupaten Karanganyar</p>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 bg-gray-100 overflow-x-hidden">
        <!-- Header -->
        <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <div class="h-6 w-32"></div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="font-medium text-gray-700">
                        {{ Auth::user()->role }}
                    </span>
                </div>
                <button id="logoutBtn" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </button>
                <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </header>

        <!-- Content -->
        <div class="p-6">
            @yield('content')
        </div>
    </main>
</div>

<!-- AlpineJS for dropdown toggle -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<!-- SweetAlert2 for logout -->
<script>
document.getElementById('logoutBtn').addEventListener('click', function () {
    Swal.fire({
        title: 'Anda yakin akan keluar?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logoutForm').submit();
        }
    });
});
</script>
@stack('scripts')
</body>
</html>