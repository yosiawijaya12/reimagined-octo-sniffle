<div id="modalSubForm" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-xl relative">
        <h2 class="text-xl font-semibold mb-4">Form Sub Kegiatan</h2>
        <form id="subForm" method="POST" action="{{ route('subkegiatan.store') }}">
            @csrf
            <input type="hidden" name="id" id="subkegiatan-id">

            <div class="mb-4">
                <label for="id_kegiatan" class="block font-medium mb-1">Kegiatan</label>
                <select name="id_kegiatan" id="id_kegiatan" required class="w-full border rounded px-3 py-2">
                    <option value="">-- Pilih Kegiatan --</option>
                    @foreach ($kegiatan as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kegiatan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="nama_subkegiatan" class="block font-medium mb-1">Nama Sub Kegiatan</label>
                <input type="text" name="nama_subkegiatan" id="nama_subkegiatan" required class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="tahun_anggaran" class="block font-medium mb-1">Tahun Anggaran</label>
                <input type="text" name="tahun_anggaran" id="tahun_anggaran" required class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="rekening" class="block font-medium mb-1">Rekening</label>
                <input type="text" name="rekening" id="rekening" required class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label for="jumlah_pagu_display" class="block font-medium mb-1">Jumlah Pagu</label>
                <input type="text" id="jumlah_pagu_display" required class="w-full border rounded px-3 py-2" placeholder="Contoh: Rp5.000.000">
                <input type="hidden" name="jumlah_pagu" id="jumlah_pagu">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeSubModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const rupiahInput = document.getElementById('jumlah_pagu_display');
    const hiddenInput = document.getElementById('jumlah_pagu');

    function formatRupiah(angka, prefix = 'Rp') {
        const number_string = angka.replace(/[^,\d]/g, '').toString();
        const split = number_string.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        const ribuan = split[0].substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            const separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        return prefix + rupiah;
    }

    function cleanRupiah(rp) {
        return rp.replace(/[^0-9]/g, '');
    }

    rupiahInput.addEventListener('input', function (e) {
        const formatted = formatRupiah(this.value);
        this.value = formatted;
        hiddenInput.value = cleanRupiah(formatted);
    });

    // Jika modal dibuka dan ingin reset field
    document.getElementById('modalSubForm').addEventListener('click', function (e) {
        if (e.target.id === 'modalSubForm') {
            rupiahInput.value = '';
            hiddenInput.value = '';
        }
    });

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
@endpush