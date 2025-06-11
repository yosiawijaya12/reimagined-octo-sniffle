<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('storage/images/logo_biru.png') }}" type="image/x-icon"/>
</head>
<body class="relative min-h-screen bg-gray-100">

    <!-- Background with semi-transparent overlay -->
    <div class="absolute inset-0">
        <img src="{{ asset('storage/images/bg.jpg') }}" alt="Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-white bg-opacity-85"></div> <!-- overlay putih semi-transparan -->
    </div>

    <!-- Main Content -->
    <div class="relative flex items-center justify-center min-h-screen z-10">
        <div class="bg-white bg-opacity-90 p-8 rounded-lg shadow-lg w-full max-w-md backdrop-blur-sm">
            
            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <img src="{{ asset('storage/images/logo_biru_crop.PNG') }}" alt="Logo" class="h-16">
            </div>

            <h2 class="text-2xl font-semibold text-center text-gray-700 mb-6">Login</h2>

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    {{ session('error') }}
                </div>
            @endif 

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-600">Bidang</label>
                    <select id="role" name="role" required 
                            class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Bidang --</option>
                        <option value="admin">Admin</option>
                        <option value="ikp">IKP</option>
                        <option value="tki">TKI</option>
                        <option value="sekretariat">Sekretariat</option>
                        <option value="verifikator">Verifikator</option>
                        <option value="bendahara">Bendahara</option>
                        <option value="kepala_dinas">Kepala Dinas</option>
                    </select>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                    <input type="text" id="email" name="email" placeholder="Masukkan email" required 
                        class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required 
                        class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition">Login</button>
            </form>
        </div>
    </div>

</body>
</html>
