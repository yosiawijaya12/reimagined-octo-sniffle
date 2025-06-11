<!-- Modal Form Kegiatan -->
<div id="modalKegiatan" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-lg shadow-lg relative">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Form Kegiatan</h2>
            <button type="button" onclick="closeKegiatanModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Tutup
            </button>
        </div>
        <form id="form-kegiatan" method="POST" action="{{ route('kegiatan.store') }}">
            @csrf
            <input type="hidden" name="id" id="kegiatan-id">

            <div class="mb-4">
                <label for="satuan_kerja" class="block font-medium">Satuan Kerja</label>
                <select id="satuan_kerja" name="satuan_kerja" class="w-full mt-1 p-2 border rounded">
                    <option value="ikp">IKP</option>
                    <option value="tki">TKI</option>
                    <option value="sekretariat">Sekretariat</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="nama_kegiatan" class="block font-medium">Nama Kegiatan</label>
                <input type="text" id="nama_kegiatan" name="nama_kegiatan" class="w-full mt-1 p-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label for="tahun" class="block font-medium">Tahun</label>
                <input type="number" id="tahun" name="tahun" class="w-full mt-1 p-2 border rounded" required>
            </div>

            <div class="mt-6 text-right">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function closeKegiatanModal() {
        document.getElementById('modalKegiatan').classList.add('hidden');
    }

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
