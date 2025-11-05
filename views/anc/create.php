<?php 
// File: views/anc/create.php (Versi Tailwind)
include __DIR__ . '/../layouts/partials/header.php'; 
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Formulir Pemeriksaan ANC</h1>
    <a href="/anc" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg shadow-sm hover:bg-gray-300">
        <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>Kembali
    </a>
</div>

<form action="/anc/store" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700">Data Pasien & Kunjungan</div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="md:col-span-2">
                <label for="pasien_id" class="block text-sm font-medium text-gray-700">Pilih Pasien <span class="text-red-500">*</span></label>
                <select id="pasien_id" name="pasien_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">-- Cari No. RM atau Nama Pasien --</option>
                    <?php foreach ($pasiens as $pasien): ?>
                        <option value="<?php echo e($pasien['id']); ?>"><?php echo e($pasien['no_rm']); ?> - <?php echo e($pasien['nama_pasien']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="tanggal_kunjungan" class="block text-sm font-medium text-gray-700">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                <input type="date" id="tanggal_kunjungan" name="tanggal_kunjungan" value="<?php echo date('Y-m-d'); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
             <div>
                <label for="usia_kehamilan" class="block text-sm font-medium text-gray-700">Usia Kehamilan (Minggu) <span class="text-red-500">*</span></label>
                <input type="number" id="usia_kehamilan" name="usia_kehamilan" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
        </div>
    </div>

    <!-- Patient History Cards -->
    <div id="patient-info-cards" class="hidden bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700">Riwayat Kesehatan Pasien</div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Basic Info Card -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-semibold text-blue-800 mb-2">Informasi Dasar</h4>
                <div class="space-y-1 text-sm">
                    <p><strong>Nama:</strong> <span id="card-nama"></span></p>
                    <p><strong>No. RM:</strong> <span id="card-no-rm"></span></p>
                    <p><strong>Tanggal Lahir:</strong> <span id="card-tanggal-lahir"></span></p>
                    <p><strong>Alamat:</strong> <span id="card-alamat"></span></p>
                    <p><strong>Telepon:</strong> <span id="card-telepon"></span></p>
                </div>
            </div>

            <!-- Pregnancy Status Card -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h4 class="font-semibold text-green-800 mb-2">Status Kehamilan</h4>
                <div class="space-y-1 text-sm">
                    <p><strong>GPA:</strong> <span id="card-gpa"></span></p>
                    <p><strong>HPHT:</strong> <span id="card-hpht"></span></p>
                    <p><strong>Nama Suami:</strong> <span id="card-suami"></span></p>
                    <p><strong>Gol. Darah:</strong> <span id="card-gol-darah"></span></p>
                </div>
            </div>

            <!-- Medical History Card -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <h4 class="font-semibold text-red-800 mb-2">Riwayat Kesehatan</h4>
                <div class="space-y-1 text-sm">
                    <p><strong>Riwayat Penyakit:</strong> <span id="card-riwayat-penyakit"></span></p>
                    <p><strong>Hipertensi:</strong> <span id="card-hipertensi"></span></p>
                    <p><strong>Diabetes:</strong> <span id="card-diabetes"></span></p>
                    <p><strong>Alergi Obat:</strong> <span id="card-alergi"></span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg">
        <div class="p-6 border-b font-semibold text-gray-700">Pemeriksaan SOAP</div>
        <div class="p-6 space-y-6">
            <div>
                <label for="keluhan" class="block text-sm font-bold text-gray-700">S: Keluhan (Subjective)</label>
                <textarea id="keluhan" name="keluhan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            </div>
            
            <hr>
            
            <div>
                <h5 class="block text-sm font-bold text-gray-700 mb-4">O: Pemeriksaan (Objective)</h5>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div><label class="block text-xs text-gray-600">BB (Kg)</label><input type="text" name="berat_badan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
                    <div><label class="block text-xs text-gray-600">TB (Cm)</label><input type="text" name="tinggi_badan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
                    <div><label class="block text-xs text-gray-600">TD (mmHg)</label><input type="text" name="tekanan_darah" placeholder="120/80" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
                    <div><label class="block text-xs text-gray-600">LILA (Cm)</label><input type="text" name="lila" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
                    <div><label class="block text-xs text-gray-600">TFU (Cm)</label><input type="text" name="tfu" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
                    <div><label class="block text-xs text-gray-600">DJJ (x/mnt)</label><input type="text" name="djj" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
                    <div class="col-span-2"><label class="block text-xs text-gray-600">Presentasi Janin</label><input type="text" name="presentasi_janin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
                    <div class="col-span-full"><label class="block text-xs text-gray-600">Hasil Lab</label><textarea name="hasil_lab" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea></div>
                </div>
            </div>

            <hr>

            <div>
                 <label for="diagnosis" class="block text-sm font-bold text-gray-700">A: Diagnosis (Assessment)</label>
                <textarea id="diagnosis" name="diagnosis" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
            </div>
            
            <hr>

            <div>
                <h5 class="block text-sm font-bold text-gray-700 mb-4">P: Perencanaan (Planning)</h5>
                <div class="space-y-4">
                    <div><label for="terapi" class="text-sm font-medium text-gray-700">Terapi / Obat</label><textarea id="terapi" name="terapi" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea></div>
                    <div><label for="konseling" class="text-sm font-medium text-gray-700">Edukasi / Konseling</label><textarea id="konseling" name="konseling" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea></div>
                    <div class="max-w-xs"><label for="jadwal_kunjungan_ulang" class="text-sm font-medium text-gray-700">Jadwal Kunjungan Ulang</label><input type="date" id="jadwal_kunjungan_ulang" name="jadwal_kunjungan_ulang" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-6">
        <button type="submit" class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
            Simpan Pemeriksaan
        </button>
    </div>
</form>

<script>
document.getElementById('pasien_id').addEventListener('change', function() {
    const pasienId = this.value;
    if (pasienId) {
        fetch(`/anc/get_patient_data?pasien_id=${pasienId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show cards
                    document.getElementById('patient-info-cards').classList.remove('hidden');

                    // Populate cards
                    document.getElementById('card-nama').textContent = data.nama_pasien || '-';
                    document.getElementById('card-no-rm').textContent = data.no_rm || '-';
                    document.getElementById('card-tanggal-lahir').textContent = data.tanggal_lahir || '-';
                    document.getElementById('card-alamat').textContent = data.alamat_lengkap || '-';
                    document.getElementById('card-telepon').textContent = data.no_telepon || '-';
                    document.getElementById('card-gpa').textContent = data.gravida_paritas || '-';
                    document.getElementById('card-hpht').textContent = data.hpht || '-';
                    document.getElementById('card-suami').textContent = data.nama_suami || '-';
                    document.getElementById('card-gol-darah').textContent = data.golongan_darah || '-';
                    document.getElementById('card-riwayat-penyakit').textContent = data.riwayat_penyakit || '-';
                    document.getElementById('card-hipertensi').textContent = data.hipertensi || '-';
                    document.getElementById('card-diabetes').textContent = data.diabetes || '-';
                    document.getElementById('card-alergi').textContent = data.alergi_obat || '-';

                    // Auto-fill usia kehamilan
                    if (data.usia_kehamilan !== null) {
                        document.getElementById('usia_kehamilan').value = data.usia_kehamilan;
                    }
                } else {
                    alert('Gagal memuat data pasien: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memuat data pasien');
            });
    } else {
        // Hide cards if no patient selected
        document.getElementById('patient-info-cards').classList.add('hidden');
        document.getElementById('usia_kehamilan').value = '';
    }
});
</script>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>
