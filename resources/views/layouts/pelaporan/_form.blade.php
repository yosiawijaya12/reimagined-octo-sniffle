<div id="modalForm" onsubmit="handleFormSubmit(event)" aria-controls=""
     class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden items-center justify-center overflow-y-auto">
    <div class="relative bg-white rounded-2xl shadow-xl max-w-4xl w-full p-8 overflow-y-auto max-h-[90vh]">
        <!-- Header Modal -->
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Form Pelaporan Proyek</h2>
                <p class="text-gray-500 text-sm">Isi formulir di bawah ini dengan lengkap dan benar.</p>
            </div>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pelaporan.store') }}" method="POST" enctype="multipart/form-data"
              class="grid grid-cols-1 md:grid-cols-2 gap-6" id="pelaporanForm">
            @csrf
            <input type="hidden" id="laporan-id" name="laporan_id">

            <!-- PPTK -->
            <div>
                <label for="pptk" class="block font-medium text-gray-700 mb-1">PPTK <span
                        class="text-red-500">*</span></label>
                <select id="pptk" name="pptk" required
                        class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="4">Sekretariat</option>
                    <option value="2">IKP (Informasi dan Komunikasi Publik)</option>
                    <option value="3">TKI (Teknologi, Komunikasi, dan Informatika)</option>
                </select>
            </div>

            <!-- Jenis Belanja -->
            <div>
                <label for="jenis" class="block font-medium text-gray-700 mb-1">Jenis Belanja <span
                        class="text-red-500">*</span></label>
                <select id="jenis" name="jenis" required
                        class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="SPJ GU">GU</option>
                    <option value="SPJ GU Tunai">GU Tunai</option>
                    <option value="Belanja Jasa Tenaga Ahli">Belanja Jasa Tenaga Ahli</option>
                    <option value="Belanja Jasa THL">Belanja Jasa THL</option>
                    <option value="LS">LS</option>
                </select>
            </div>

            <!-- Kegiatan -->
            <div>
                <label for="kegiatan" class="block font-medium text-gray-700 mb-1">Kegiatan</label>
                <select id="kegiatan" name="kegiatan" required
                        class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">-- Pilih PPTK Dulu --</option>
                </select>
            </div>

            <!-- Sub Kegiatan -->
            <div>
                <label for="sub-kegiatan" class="block font-medium text-gray-700 mb-1">Sub Kegiatan</label>
                <select id="sub-kegiatan" name="sub_kegiatan" required
                        class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">-- Pilih Kegiatan Dulu --</option>
                </select>
            </div>

            <input type="hidden" name="subkegiatan_id" id="subkegiatan-id">

            <!-- Rekening Kegiatan -->
            <div>
                <label for="rek-keg" class="block font-medium text-gray-700 mb-1">Rekening Kegiatan</label>
                <input type="text" name="rekening_kegiatan" id="rek-keg"
                       class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>

            <!-- Periode -->
            <div>
                <label for="periode" class="block font-medium text-gray-700 mb-1">Periode (Tahun)</label>
                <select id="periode" name="periode" required
                        class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    @for ($i = date('Y'); $i >= 2000; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <!-- Jumlah Pagu -->
            <div>
                <label for="jumlah-pagu" class="block font-medium text-gray-700 mb-1">Jumlah Pagu <span
                        class="text-red-500">*</span></label>
                <input type="text" name="jumlah_pagu" id="jumlah-pagu" value="{{ old('jumlah_pagu') }}"
                       oninput="formatRupiah(this)"
                       class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>

            <!-- Anggaran Sekarang -->
            <div>
                <label for="anggaran-sekarang" class="block font-medium text-gray-700 mb-1">Anggaran Sekarang <span
                        class="text-red-500">*</span></label>
                <input type="text" name="anggaran_sekarang" id="anggaran-sekarang" required
                       value="{{ old('anggaran_sekarang') }}" oninput="formatRupiah(this); validateAnggaran()"
                       class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                <p id="error-anggaran" class="text-red-500 text-sm hidden mt-1">Anggaran tidak boleh melebihi Jumlah
                    Pagu!</p>
            </div>

            <!-- File Upload -->
            <div class="md:col-span-2">
                <label for="file-upload" class="block font-medium text-gray-700 mb-2">Upload File (PDF)</label>
                <label
                    class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg py-6 hover:bg-gray-50 cursor-pointer">
                    <i class="fas fa-cloud-upload-alt text-3xl text-blue-600"></i>
                    <span class="mt-2 text-sm text-gray-600">Klik untuk upload file</span>
                    <input type="file" name="file-upload" id="file-upload" accept=".pdf" class="hidden">
                </label>
                <div id="file-preview" class="mt-4"></div>
            </div>

            <input type="hidden" name="catatan" id="catatan" value="-">

            <!-- Submit -->
            <div class="md:col-span-2 mt-4">
                <button type="submit" id="submit-button"
                        class="w-full py-3 text-white text-lg font-semibold bg-blue-600 rounded hover:bg-blue-700 shadow transition duration-200">
                    <i class="fas fa-paper-plane mr-2"></i>Submit Laporan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        let formSubmitting = false; // Flag to prevent multiple submissions

        function openModal() {
            document.getElementById('modalForm').classList.remove('hidden');
            document.getElementById('modalForm').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('modalForm').classList.add('hidden');
        }

        function formatRupiah(el) {
            let value = el.value.replace(/[^0-9]/g, '');
            el.value = new Intl.NumberFormat('id-ID').format(value);
        }

        function validateAnggaran() {
            let pagu = document.getElementById('jumlah-pagu').value.replace(/\./g, '').replace(/,/g, '');
            let anggaran = document.getElementById('anggaran-sekarang').value.replace(/\./g, '').replace(/,/g, '');
            if (parseInt(anggaran) > parseInt(pagu)) {
                document.getElementById('error-anggaran').classList.remove('hidden');
                document.getElementById('submit-button').disabled = true;
                document.getElementById('submit-button').classList.add('bg-gray-400', 'cursor-not-allowed');
                document.getElementById('submit-button').classList.remove('bg-blue-600', 'hover:bg-blue-700');
            } else {
                document.getElementById('error-anggaran').classList.add('hidden');
                document.getElementById('submit-button').disabled = false;
                document.getElementById('submit-button').classList.remove('bg-gray-400', 'cursor-not-allowed');
                document.getElementById('submit-button').classList.add('bg-blue-600', 'hover:bg-blue-700');
            }
        }

        function editLaporan(data) {
            openModal();
            const form = document.querySelector('#modalForm form');
            form.action = `/pelaporan/update/${data.id}`; // Ganti action form
            document.getElementById('laporan-id').value = data.id;
            document.getElementById('pptk').value = data.pptk_id;
            document.getElementById('jenis').value = data.jenis_belanja;
            document.getElementById('periode').value = data.periode;
            document.getElementById('rek-keg').value = data.rekening_kegiatan;
            document.getElementById('jumlah-pagu').value = new Intl.NumberFormat('id-ID').format(data.nominal_pagu);
            document.getElementById('anggaran-sekarang').value = new Intl.NumberFormat('id-ID').format(data.nominal);
            // Trigger perubahan PPTK untuk memuat kegiatan dan subkegiatan
            const pptkEvent = new Event('change');
            document.getElementById('pptk').dispatchEvent(pptkEvent);
            setTimeout(() => {
                document.getElementById('kegiatan').value = data.kegiatan_id;
                const kegiatanEvent = new Event('change');
                document.getElementById('kegiatan').dispatchEvent(kegiatanEvent);
                setTimeout(() => {
                    document.getElementById('sub-kegiatan').value = data.subkegiatan_id || '';
                    document.getElementById('subkegiatan-id').value = data.subkegiatan_id || '';
                }, 300);
            }, 300);
        }

        function handleFormSubmit(event) {
            event.preventDefault(); // Prevent default form submission

            if (formSubmitting) {
                console.log('Form is already submitting...');
                return; // Prevent multiple submissions
            }

            formSubmitting = true; // Set the flag

            const form = event.target;
            const jumlahPagu = document.getElementById('jumlah-pagu');
            const anggaran = document.getElementById('anggaran-sekarang');

            // Clean up the formatted numbers
            if (jumlahPagu) jumlahPagu.value = jumlahPagu.value.replace(/[^\d]/g, '');
            if (anggaran) anggaran.value = anggaran.value.replace(/[^\d]/g, '');

            // Set action only if it's a new submission (not an update)
            const laporanId = document.getElementById('laporan-id').value;
            if (!laporanId) {
                form.action = `{{ route('pelaporan.store') }}`;
            }

            // Set catatan
            document.getElementById('catatan').value = "-";

            // Disable the submit button
            const submitButton = document.getElementById('submit-button');
            submitButton.disabled = true;
            submitButton.classList.add('bg-gray-400', 'cursor-not-allowed');
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...'; // Change button text

            // Use fetch API to submit the form
            fetch(form.action, {
                method: form.method,
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Data berhasil disimpan.',
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                            willClose: () => {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menyimpan data.',
                            icon: 'error',
                            timer: 2000,
                            timerProgressBar: true
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menyimpan data.',
                        icon: 'error',
                        timer: 2000,
                        timerProgressBar: true
                    });
                })
                .finally(() => {
                    formSubmitting = false; // Reset the flag
                    submitButton.disabled = false; // Re-enable the button
                    submitButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
                    submitButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
                    submitButton.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Submit Laporan'; // Restore button text
                });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('#modalForm form');
            const jumlahPagu = document.getElementById('jumlah-pagu');
            const anggaran = document.getElementById('anggaran-sekarang');
            const subKegiatanSelect = document.getElementById('sub-kegiatan');
            const subkegiatanIdInput = document.getElementById('subkegiatan-id');

            // Saat pilih sub-kegiatan, set value ke hidden input
            subKegiatanSelect.addEventListener('change', function () {
                subkegiatanIdInput.value = this.value || '';
            });

            // Attach the submit handler to the form
            form.addEventListener('submit', handleFormSubmit);

            // Saat DOM siap, format nilai jika sudah ada
            if (jumlahPagu?.value) formatRupiah(jumlahPagu);
            if (anggaran?.value) formatRupiah(anggaran);

            const pptkSelect = document.getElementById('pptk');
            const kegiatanSelect = document.getElementById('kegiatan');
            const rekeningInput = document.getElementById('rek-keg');
            const paguInput = document.getElementById('jumlah-pagu');
            const fileInput = document.getElementById('file-upload');

            pptkSelect.addEventListener('change', function () {
                const pptk = this.value;
                let satuanKerja = '';
                if (pptk == '4') satuanKerja = 'sekretariat';
                else if (pptk == '2') satuanKerja = 'ikp';
                else if (pptk == '3') satuanKerja = 'tki';

                if (satuanKerja !== '') {
                    fetch(`/api/kegiatan/${pptk}`)
                        .then(response => response.json())
                        .then(data => {
                            kegiatanSelect.innerHTML = '<option value="">-- Pilih Kegiatan --</option>';
                            subKegiatanSelect.innerHTML = '<option value="">-- Pilih Kegiatan Dulu --</option>';
                            rekeningInput.value = '';
                            paguInput.value = '';

                                                        data.filter(kegiatan => kegiatan.satuan_kerja.toLowerCase() === satuanKerja)
                                .forEach(kegiatan => {
                                    const option = document.createElement('option');
                                    option.value = kegiatan.id;
                                    option.textContent = kegiatan.nama_kegiatan;
                                    kegiatanSelect.appendChild(option);
                                });
                        });
                } else {
                    kegiatanSelect.innerHTML = '<option value="">-- Pilih PPTK dulu --</option>';
                    subKegiatanSelect.innerHTML = '<option value="">-- Pilih Kegiatan Dulu --</option>';
                }
            });

            kegiatanSelect.addEventListener('change', function () {
                const kegiatanId = this.value;
                subKegiatanSelect.innerHTML = '<option value="">-- Pilih Sub Kegiatan --</option>';
                rekeningInput.value = '';
                paguInput.value = '';

                if (kegiatanId) {
                    fetch(`/api/subkegiatan/${kegiatanId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(subkegiatan => {
                                const option = document.createElement('option');
                                option.value = subkegiatan.id;
                                option.textContent = subkegiatan.nama_subkegiatan;
                                subKegiatanSelect.appendChild(option);
                            });
                        });
                }
            });

            subKegiatanSelect.addEventListener('change', function () {
                const subId = this.value;
                if (subId) {
                    fetch(`/api/subkegiatan/detail/${subId}`)
                        .then(response => response.json())
                        .then(data => {
                            rekeningInput.value = data.rekening || '';
                            paguInput.value = new Intl.NumberFormat('id-ID').format(data.jumlah_pagu || 0);
                            rekeningInput.setAttribute('readonly', true);
                            rekeningInput.classList.add('bg-gray-100');
                            paguInput.setAttribute('readonly', true);
                            paguInput.classList.add('bg-gray-100');
                        });
                } else {
                    rekeningInput.value = '';
                    paguInput.value = '';
                }
            });

            fileInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const preview = document.createElement('div');
                    preview.className = 'mt-4 text-sm text-gray-700';
                    const fileURL = URL.createObjectURL(file);

                    if (file.type === 'application/pdf') {
                        preview.innerHTML = `
                            <p>Nama File: ${file.name}</p>
                            <iframe src="${fileURL}" class="w-full h-64 mt-2 border rounded" frameborder="0"></iframe>
                        `;
                    } else {
                        preview.innerHTML = `<p>File: ${file.name}</p>`;
                    }

                    const existingPreview = document.getElementById('file-preview');
                    if (existingPreview) existingPreview.remove();
                    preview.id = 'file-preview';
                    fileInput.parentElement.appendChild(preview);

                    preview.querySelector('iframe')?.addEventListener('load', () => {
                        URL.revokeObjectURL(fileURL);
                    });
                }
            });

            if (anggaran) {
                anggaran.addEventListener('input', validateAnggaran);
            }
        });
    </script>
@endpush
