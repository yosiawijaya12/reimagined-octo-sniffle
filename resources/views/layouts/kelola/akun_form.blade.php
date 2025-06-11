<div id="modalAkunForm" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg relative">
        <h2 class="text-xl font-semibold mb-4">Form Akun</h2>
        <form id="akunForm" method="POST" action="{{ route('akun.store') }}">
            @csrf
            <input type="hidden" name="id" id="akun-id">

            <div class="mb-4">
                <label for="name" class="block font-medium mb-1">Nama</label>
                <input type="text" name="name" id="name" required class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="email" class="block font-medium mb-1">Email</label>
                <input type="email" name="email" id="email" required class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="role" class="block font-medium mb-1">Role</label>
                <select name="role" id="role" required class="w-full border rounded px-3 py-2">
                    <option value="">-- Pilih Role --</option>
                    <option value="ikp">IKP</option>
                    <option value="tki">TKI</option>
                    <option value="sekretariat">Sekretariat</option>
                    <option value="verifikator">Verifikator</option>
                    <option value="bendahara">Bendahara</option>
                    <option value="kepala_dinas">Kepala Dinas</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="password" class="block font-medium mb-1">Password</label>
                <input type="password" name="password" id="password" required class="w-full border rounded px-3 py-2">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeAkunModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('success'))
            Swal.fire({
                title: 'Sukses!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK',
                timer: 2000, // Auto close after 2000ms
                timerProgressBar: true,
            });
        @elseif (session('error'))
            Swal.fire({
                title: 'Gagal!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'OK',
                timer: 2000, // Auto close after 2000ms
                timerProgressBar: true,
            });
        @endif
    });
</script>