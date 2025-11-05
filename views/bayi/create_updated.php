<?php 
// File: views/bayi/create.php - Sesuai Standar Pelayanan Neonatal Esensial Kemenkes RI
include __DIR__ . '/../layouts/partials/header.php'; 
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Formulir Pendaftaran Bayi Baru Lahir</h1>
    <a href="/bayi" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg shadow-sm hover:bg-gray-300">
        <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>Kembali
    </a>
</div>

<form action="/bayi/store" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

    <!-- Data Ibu & Bayi -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-pink-50">
            <i data-lucide="users" class="inline h-5 w-5 mr-2"></i>Data Ibu & Identitas Bayi
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="pasien_id" class="block text-sm font-medium text-gray-700">Pilih Ibu (Pasien) <span class="text-red-500">*</span></label>
                <select id="pasien_id" name="pasien_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Pilih Ibu --</option>
                    <?php foreach ($pasiens as $pasien): ?>
                        <option value="<?php echo e($pasien['id']); ?>"><?php echo e($pasien['no_rm']); ?> - <?php echo e($pasien['nama_pasien']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="nama_bayi" class="block text-sm font-medium text-gray-700">Nama Bayi <span class="text-red-500">*</span></label>
                <input type="text" id="nama_bayi" name="nama_bayi" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Nama lengkap bayi">
            </div>
            <div>
                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir <span class="text-red-500">*</span></label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" required value="<?php echo date('Y-m-d'); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label for="jam_lahir" class="block text-sm font-medium text-gray-700">Jam Lahir <span class="text-red-500">*</span></label>
                <input type="time" id="jam_lahir" name="jam_lahir" required value="<?php echo date('H:i'); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin <span class="text-red-500">*</span></label>
                <select id="jenis_kelamin" name="jenis_kelamin" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <div>
                <label for="anak_ke" class="block text-sm font-medium text-gray-700">Anak Ke-</label>
                <input type="number" id="anak_ke" name="anak_ke" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="1">
            </div>
        </div>
    </div>

    <!-- Antropometri Bayi -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-blue-50">
            <i data-lucide="ruler" class="inline h-5 w-5 mr-2"></i>Antropometri Bayi Baru Lahir
        </div>
        <div class="p-6 grid grid-cols-2 md:grid-cols-4 gap-6">
            <div>
                <label for="berat_lahir" class="block text-sm font-medium text-gray-700">Berat Lahir (gram) <span class="text-red-500">*</span></label>
                <input type="number" id="berat_lahir" name="berat_lahir" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="3200">
                <p class="text-xs text-gray-500 mt-1">Normal: 2500-4000 gram</p>
            </div>
            <div>
                <label for="panjang_lahir" class="block text-sm font-medium text-gray-700">Panjang Lahir (cm) <span class="text-red-500">*</span></label>
                <input type="number" id="panjang_lahir" name="panjang_lahir" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="50">
                <p class="text-xs text-gray-500 mt-1">Normal: 48-52 cm</p>
            </div>
            <div>
                <label for="lingkar_kepala" class="block text-sm font-medium text-gray-700">Lingkar Kepala (cm) <span class="text-red-500">*</span></label>
                <input type="number" id="lingkar_kepala" name="lingkar_kepala" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="34">
                <p class="text-xs text-gray-500 mt-1">Normal: 33-37 cm</p>
            </div>
            <div>
                <label for="golongan_darah" class="block text-sm font-medium text-gray-700">Golongan Darah</label>
                <select id="golongan_darah" name="golongan_darah" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Pilih --</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="AB">AB</option>
                    <option value="O">O</option>
                    <option value="Belum Diperiksa">Belum Diperiksa</option>
                </select>
            </div>
        </div>
    </div>

    <!-- APGAR Score -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-green-50">
            <i data-lucide="heart-pulse" class="inline h-5 w-5 mr-2"></i>APGAR Score (Penilaian Kondisi Bayi)
        </div>
        <div class="p-6 space-y-6">
            <div class="bg-blue-50 p-4 rounded-lg">
                <h6 class="font-semibold text-gray-700 mb-3">Panduan APGAR Score</h6>
                <div class="text-xs text-gray-600 space-y-1">
                    <p><strong>A</strong>ppearance (Warna Kulit): 0=Biru/Pucat, 1=Tubuh Merah Ekstremitas Biru, 2=Seluruh Tubuh Merah</p>
                    <p><strong>P</strong>ulse (Denyut Jantung): 0=Tidak Ada, 1=<100x/menit, 2=>100x/menit</p>
                    <p><strong>G</strong>rimace (Refleks): 0=Tidak Ada, 1=Meringis, 2=Menangis Kuat</p>
                    <p><strong>A</strong>ctivity (Tonus Otot): 0=Lemas, 1=Fleksi Lemah, 2=Gerakan Aktif</p>
                    <p><strong>R</strong>espiration (Pernapasan): 0=Tidak Ada, 1=Lemah/Tidak Teratur, 2=Menangis Kuat</p>
                    <p class="mt-2"><strong>Interpretasi:</strong> 7-10=Normal, 4-6=Asfiksia Ringan-Sedang, 0-3=Asfiksia Berat</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="apgar_1_menit" class="block text-sm font-medium text-gray-700">APGAR Score 1 Menit <span class="text-red-500">*</span></label>
                    <input type="number" id="apgar_1_menit" name="apgar_1_menit" required min="0" max="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="0-10">
                </div>
                <div>
                    <label for="apgar_5_menit" class="block text-sm font-medium text-gray-700">APGAR Score 5 Menit <span class="text-red-500">*</span></label>
                    <input type="number" id="apgar_5_menit" name="apgar_5_menit" required min="0" max="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="0-10">
                </div>
            </div>

            <div>
                <label for="kondisi_lahir" class="block text-sm font-medium text-gray-700">Kondisi Saat Lahir <span class="text-red-500">*</span></label>
                <select id="kondisi_lahir" name="kondisi_lahir" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Pilih --</option>
                    <option value="Sehat">Sehat (APGAR 7-10)</option>
                    <option value="Asfiksia Ringan">Asfiksia Ringan (APGAR 4-6)</option>
                    <option value="Asfiksia Sedang">Asfiksia Sedang (APGAR 4-6)</option>
                    <option value="Asfiksia Berat">Asfiksia Berat (APGAR 0-3)</option>
                    <option value="Lahir Mati">Lahir Mati</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Resusitasi -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-yellow-50">
            <i data-lucide="wind" class="inline h-5 w-5 mr-2"></i>Resusitasi Bayi Baru Lahir
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="resusitasi" class="block text-sm font-medium text-gray-700">Dilakukan Resusitasi <span class="text-red-500">*</span></label>
                    <select id="resusitasi" name="resusitasi" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Pilih --</option>
                        <option value="Ya">Ya</option>
                        <option value="Tidak">Tidak</option>
                    </select>
                </div>
                <div>
                    <label for="jenis_resusitasi" class="block text-sm font-medium text-gray-700">Jenis Resusitasi</label>
                    <input type="text" id="jenis_resusitasi" name="jenis_resusitasi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="VTP, Kompresi Dada, Intubasi, dll">
                </div>
            </div>
        </div>
    </div>

    <!-- Pelayanan Neonatal Esensial -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-purple-50">
            <i data-lucide="syringe" class="inline h-5 w-5 mr-2"></i>Pelayanan Neonatal Esensial (Wajib)
        </div>
        <div class="p-6 space-y-6">
            <div class="bg-green-50 p-4 rounded-lg">
                <h6 class="font-semibold text-gray-700 mb-3">1. Inisiasi Menyusu Dini (IMD)</h6>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">IMD Dilakukan <span class="text-red-500">*</span></label>
                        <select name="imd" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Ya">Ya, Berhasil</option>
                            <option value="Tidak">Tidak</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Lama IMD (menit)</label>
                        <input type="number" name="lama_imd" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="60">
                        <p class="text-xs text-gray-500 mt-1">Minimal 1 jam (60 menit)</p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg">
                <h6 class="font-semibold text-gray-700 mb-3">2. Injeksi Vitamin K1</h6>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Vitamin K1 Diberikan <span class="text-red-500">*</span></label>
                        <select name="vitamin_k1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Ya">Ya, 1 mg IM</option>
                            <option value="Tidak">Tidak/Ditunda</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Diberikan 1 mg IM di paha kiri</p>
                    </div>
                </div>
            </div>

            <div class="bg-orange-50 p-4 rounded-lg">
                <h6 class="font-semibold text-gray-700 mb-3">3. Salep Mata (Profilaksis Infeksi)</h6>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Salep Mata Diberikan <span class="text-red-500">*</span></label>
                        <select name="salep_mata" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Ya">Ya, Tetrasiklin 1%</option>
                            <option value="Tidak">Tidak/Ditunda</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Tetrasiklin 1% atau Eritromisin 0.5%</p>
                    </div>
                </div>
            </div>

            <div class="bg-pink-50 p-4 rounded-lg">
                <h6 class="font-semibold text-gray-700 mb-3">4. Imunisasi Hepatitis B-0 (HB-0)</h6>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">HB-0 Diberikan <span class="text-red-500">*</span></label>
                        <select name="hb0_imunisasi" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Ya">Ya, <24 jam</option>
                            <option value="Tidak">Tidak/Ditunda</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Diberikan dalam 24 jam pertama</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Waktu Pemberian</label>
                        <input type="text" name="waktu_hb0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="2 jam setelah lahir">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kelainan Kongenital & Catatan -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-red-50">
            <i data-lucide="alert-circle" class="inline h-5 w-5 mr-2"></i>Kelainan Kongenital & Catatan Kelahiran
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label for="kelainan_kongenital" class="block text-sm font-medium text-gray-700">Kelainan Kongenital (jika ada)</label>
                <textarea id="kelainan_kongenital" name="kelainan_kongenital" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Sebutkan kelainan bawaan jika ada, atau tulis 'Tidak Ada'"></textarea>
                <p class="text-xs text-gray-500 mt-1">Contoh: Bibir sumbing, Down syndrome, kelainan jantung, dll</p>
            </div>
            <div>
                <label for="catatan_kelahiran" class="block text-sm font-medium text-gray-700">Catatan Kelahiran</label>
                <textarea id="catatan_kelahiran" name="catatan_kelahiran" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Catatan penting lainnya tentang kelahiran bayi..."></textarea>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="mt-6">
        <button type="submit" class="w-full inline-flex justify-center items-center py-3 px-4 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
            <i data-lucide="save" class="mr-2 h-5 w-5"></i>
            Simpan Data Bayi
        </button>
    </div>
</form>

<script>
// Auto-update kondisi_lahir based on APGAR scores
document.getElementById('apgar_1_menit').addEventListener('change', updateKondisiLahir);
document.getElementById('apgar_5_menit').addEventListener('change', updateKondisiLahir);

function updateKondisiLahir() {
    const apgar1 = parseInt(document.getElementById('apgar_1_menit').value) || 0;
    const apgar5 = parseInt(document.getElementById('apgar_5_menit').value) || 0;
    const kondisiSelect = document.getElementById('kondisi_lahir');
    
    // Use the 5-minute APGAR score for classification
    const apgar = apgar5 > 0 ? apgar5 : apgar1;
    
    if (apgar >= 7) {
        kondisiSelect.value = 'Sehat';
    } else if (apgar >= 4) {
        kondisiSelect.value = 'Asfiksia Ringan';
    } else if (apgar > 0) {
        kondisiSelect.value = 'Asfiksia Berat';
    }
}

// Show/hide resusitasi details
document.getElementById('resusitasi').addEventListener('change', function() {
    const jenisInput = document.getElementById('jenis_resusitasi');
    if (this.value === 'Ya') {
        jenisInput.required = true;
        jenisInput.parentElement.classList.remove('hidden');
    } else {
        jenisInput.required = false;
        jenisInput.value = '';
    }
});
</script>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>
