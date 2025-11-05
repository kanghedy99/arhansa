<?php 
// File: views/kb/create.php - Sesuai Standar Pelayanan KB Kemenkes RI
include __DIR__ . '/../layouts/partials/header.php'; 
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Formulir Pelayanan KB (Keluarga Berencana)</h1>
    <a href="/kb" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg shadow-sm hover:bg-gray-300">
        <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>Kembali
    </a>
</div>

<form action="/kb/store" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

    <!-- Data Pasien & Layanan -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-teal-50">
            <i data-lucide="user" class="inline h-5 w-5 mr-2"></i>Data Pasien & Jenis Layanan
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2">
                <label for="pasien_id" class="block text-sm font-medium text-gray-700">Pilih Pasien <span class="text-red-500">*</span></label>
                <select id="pasien_id" name="pasien_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Pilih Pasien --</option>
                    <?php foreach ($pasiens as $pasien): ?>
                        <option value="<?php echo e($pasien['id']); ?>"><?php echo e($pasien['no_rm']); ?> - <?php echo e($pasien['nama_pasien']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="tanggal_layanan" class="block text-sm font-medium text-gray-700">Tanggal Layanan <span class="text-red-500">*</span></label>
                <input type="date" id="tanggal_layanan" name="tanggal_layanan" value="<?php echo date('Y-m-d'); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label for="jenis_layanan" class="block text-sm font-medium text-gray-700">Jenis Layanan <span class="text-red-500">*</span></label>
                <select id="jenis_layanan" name="jenis_layanan" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Pilih --</option>
                    <option value="Baru">Akseptor Baru</option>
                    <option value="Kunjungan Ulang">Kunjungan Ulang</option>
                    <option value="Ganti Metode">Ganti Metode KB</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label for="status_kb" class="block text-sm font-medium text-gray-700">Status KB</label>
                <select id="status_kb" name="status_kb" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Pilih --</option>
                    <option value="Aktif">Aktif</option>
                    <option value="Berhenti">Berhenti</option>
                    <option value="Ganti Metode">Ganti Metode</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Metode KB -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-purple-50">
            <i data-lucide="shield" class="inline h-5 w-5 mr-2"></i>Metode KB yang Dipilih
        </div>
        <div class="p-6">
            <label for="metode_kb" class="block text-sm font-medium text-gray-700 mb-2">Pilih Metode KB <span class="text-red-500">*</span></label>
            <select id="metode_kb" name="metode_kb" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">-- Pilih Metode KB --</option>
                
                <optgroup label="=== KB HORMONAL ===">
                    <option value="Pil KB Kombinasi">Pil KB Kombinasi</option>
                    <option value="Pil KB Mini (Progestin)">Pil KB Mini (Progestin)</option>
                    <option value="Suntik 1 Bulan">Suntik 1 Bulan (Cyclofem)</option>
                    <option value="Suntik 3 Bulan">Suntik 3 Bulan (Depo Provera)</option>
                    <option value="Implan/Susuk">Implan/Susuk (3 tahun)</option>
                </optgroup>
                
                <optgroup label="=== KB NON-HORMONAL ===">
                    <option value="IUD/Spiral Copper">IUD/Spiral Copper (10 tahun)</option>
                    <option value="IUD Hormonal">IUD Hormonal (5 tahun)</option>
                    <option value="Kondom">Kondom</option>
                </optgroup>
                
                <optgroup label="=== KB PERMANEN ===">
                    <option value="MOW (Tubektomi)">MOW - Tubektomi (Sterilisasi Wanita)</option>
                    <option value="MOP (Vasektomi)">MOP - Vasektomi (Sterilisasi Pria)</option>
                </optgroup>
                
                <optgroup label="=== KB ALAMI ===">
                    <option value="MAL (Metode Amenore Laktasi)">MAL - Metode Amenore Laktasi</option>
                    <option value="Kalender/Pantang Berkala">Kalender/Pantang Berkala</option>
                    <option value="Suhu Basal Tubuh">Suhu Basal Tubuh</option>
                    <option value="Metode Lendir Serviks">Metode Lendir Serviks</option>
                </optgroup>
            </select>
            <p class="text-xs text-gray-500 mt-2">Pilih metode KB yang sesuai dengan kondisi dan kebutuhan pasien</p>
        </div>
    </div>

    <!-- Skrining Kesehatan -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-yellow-50">
            <i data-lucide="clipboard-check" class="inline h-5 w-5 mr-2"></i>Skrining Kesehatan Sebelum KB
        </div>
        <div class="p-6 space-y-6">
            
            <!-- Tanda Vital -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <h6 class="font-semibold text-gray-700 mb-3">Pemeriksaan Tanda Vital</h6>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tekanan Darah <span class="text-red-500">*</span></label>
                        <input type="text" name="tekanan_darah" required placeholder="120/80" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Berat Badan (Kg) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.1" name="berat_badan" required placeholder="55.5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tinggi Badan (Cm)</label>
                        <input type="number" name="tinggi_badan" placeholder="160" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">IMT (BMI)</label>
                        <input type="text" id="imt_display" readonly placeholder="Auto" class="mt-1 block w-full rounded-md border-gray-200 bg-gray-50 shadow-sm">
                    </div>
                </div>
            </div>

            <!-- Riwayat Kesehatan -->
            <div class="bg-red-50 p-4 rounded-lg">
                <h6 class="font-semibold text-gray-700 mb-3">Riwayat Kesehatan & Kontraindikasi</h6>
                <div class="space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="hipertensi" name="riwayat_hipertensi" value="Ya" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label for="hipertensi" class="ml-2 text-sm text-gray-700">Hipertensi (TD >140/90)</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="diabetes" name="riwayat_diabetes" value="Ya" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label for="diabetes" class="ml-2 text-sm text-gray-700">Diabetes Mellitus</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="jantung" name="riwayat_jantung" value="Ya" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label for="jantung" class="ml-2 text-sm text-gray-700">Penyakit Jantung</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="stroke" name="riwayat_stroke" value="Ya" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label for="stroke" class="ml-2 text-sm text-gray-700">Riwayat Stroke</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="migrain" name="riwayat_migrain" value="Ya" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label for="migrain" class="ml-2 text-sm text-gray-700">Migrain dengan Aura</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="kanker" name="riwayat_kanker" value="Ya" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label for="kanker" class="ml-2 text-sm text-gray-700">Kanker Payudara/Serviks</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="liver" name="riwayat_liver" value="Ya" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label for="liver" class="ml-2 text-sm text-gray-700">Penyakit Liver</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="merokok" name="kebiasaan_merokok" value="Ya" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label for="merokok" class="ml-2 text-sm text-gray-700">Merokok (>35 tahun)</label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Riwayat Kesehatan Lainnya</label>
                        <textarea name="riwayat_kesehatan" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Sebutkan riwayat penyakit atau kondisi kesehatan lainnya..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Riwayat Obstetri -->
            <div class="bg-green-50 p-4 rounded-lg">
                <h6 class="font-semibold text-gray-700 mb-3">Riwayat Obstetri</h6>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="hamil" name="sedang_hamil" value="Ya" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="hamil" class="ml-2 text-sm text-gray-700">Sedang Hamil</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="menyusui" name="sedang_menyusui" value="Ya" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="menyusui" class="ml-2 text-sm text-gray-700">Sedang Menyusui</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="nifas" name="masa_nifas" value="Ya" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="nifas" class="ml-2 text-sm text-gray-700">Masa Nifas (<42 hari)</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Konseling KB -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-indigo-50">
            <i data-lucide="message-circle" class="inline h-5 w-5 mr-2"></i>Konseling & Informed Consent
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label for="konseling" class="block text-sm font-medium text-gray-700">Konseling yang Diberikan <span class="text-red-500">*</span></label>
                <textarea id="konseling" name="konseling" required rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Jelaskan konseling yang diberikan: cara kerja KB, efektivitas, efek samping, cara penggunaan, dll..."></textarea>
                <div class="mt-2 text-xs text-gray-600 bg-blue-50 p-3 rounded">
                    <p class="font-semibold mb-1">Poin Konseling KB:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Cara kerja metode KB yang dipilih</li>
                        <li>Efektivitas dan tingkat kegagalan</li>
                        <li>Efek samping yang mungkin terjadi</li>
                        <li>Cara penggunaan yang benar</li>
                        <li>Kapan harus kembali kontrol</li>
                        <li>Tanda bahaya yang perlu diwaspadai</li>
                        <li>Hak untuk mengganti atau menghentikan KB</li>
                    </ul>
                </div>
            </div>

            <div>
                <label for="informed_consent" class="block text-sm font-medium text-gray-700">Informed Consent <span class="text-red-500">*</span></label>
                <select id="informed_consent" name="informed_consent" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Pilih --</option>
                    <option value="Ya">Ya, Pasien Setuju & Menandatangani</option>
                    <option value="Tidak">Tidak Setuju</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Pasien telah mendapat penjelasan lengkap dan menyetujui penggunaan KB</p>
            </div>
        </div>
    </div>

    <!-- Keluhan & Tindakan -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-orange-50">
            <i data-lucide="stethoscope" class="inline h-5 w-5 mr-2"></i>Keluhan & Tindakan
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label for="keluhan" class="block text-sm font-medium text-gray-700">Keluhan (jika ada)</label>
                <textarea id="keluhan" name="keluhan" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Keluhan terkait KB yang sedang digunakan..."></textarea>
            </div>
            <div>
                <label for="efek_samping" class="block text-sm font-medium text-gray-700">Efek Samping yang Dialami</label>
                <textarea id="efek_samping" name="efek_samping" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Efek samping yang dialami pasien (spotting, mual, sakit kepala, dll)..."></textarea>
            </div>
            <div>
                <label for="tindakan" class="block text-sm font-medium text-gray-700">Tindakan yang Dilakukan <span class="text-red-500">*</span></label>
                <textarea id="tindakan" name="tindakan" required rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Tindakan: pemasangan KB, pemberian obat, edukasi, dll..."></textarea>
            </div>
        </div>
    </div>

    <!-- Jadwal Kembali & Biaya -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-gray-50">
            <i data-lucide="calendar" class="inline h-5 w-5 mr-2"></i>Jadwal Kembali & Biaya
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="jadwal_kembali" class="block text-sm font-medium text-gray-700">Jadwal Kunjungan Berikutnya</label>
                <input type="date" id="jadwal_kembali" name="jadwal_kembali" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <p class="text-xs text-gray-500 mt-1">
                    Pil: 1 bulan | Suntik 1 bulan: 1 bulan | Suntik 3 bulan: 3 bulan | IUD/Implan: 1 tahun
                </p>
            </div>
            <div>
                <label for="biaya" class="block text-sm font-medium text-gray-700">Biaya Layanan (Rp)</label>
                <input type="number" id="biaya" name="biaya" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="50000">
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="mt-6">
        <button type="submit" class="w-full inline-flex justify-center items-center py-3 px-4 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
            <i data-lucide="save" class="mr-2 h-5 w-5"></i>
            Simpan Layanan KB
        </button>
    </div>
</form>

<script>
// Calculate BMI automatically
document.querySelector('input[name="berat_badan"]').addEventListener('input', calculateBMI);
document.querySelector('input[name="tinggi_badan"]').addEventListener('input', calculateBMI);

function calculateBMI() {
    const weight = parseFloat(document.querySelector('input[name="berat_badan"]').value);
    const height = parseFloat(document.querySelector('input[name="tinggi_badan"]').value) / 100; // convert to meters
    
    if (weight && height) {
        const bmi = (weight / (height * height)).toFixed(1);
        let category = '';
        
        if (bmi < 18.5) category = 'Kurus';
        else if (bmi < 25) category = 'Normal';
        else if (bmi < 30) category = 'Gemuk';
        else category = 'Obesitas';
        
        document.getElementById('imt_display').value = `${bmi} (${category})`;
    }
}

// Auto-set jadwal_kembali based on metode_kb
document.getElementById('metode_kb').addEventListener('change', function() {
    const today = new Date();
    const jadwalInput = document.getElementById('jadwal_kembali');
    let months = 0;
    
    if (this.value.includes('Pil') || this.value.includes('Suntik 1 Bulan')) {
        months = 1;
    } else if (this.value.includes('Suntik 3 Bulan')) {
        months = 3;
    } else if (this.value.includes('IUD') || this.value.includes('Implan')) {
        months = 12;
    }
    
    if (months > 0) {
        today.setMonth(today.getMonth() + months);
        jadwalInput.value = today.toISOString().split('T')[0];
    }
});
</script>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>
