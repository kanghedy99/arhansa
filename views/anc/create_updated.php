<?php 
// File: views/anc/create.php - Sesuai Standar Kemenkes RI (Pemeriksaan 10T)
include __DIR__ . '/../layouts/partials/header.php'; 
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Formulir Pemeriksaan ANC (Antenatal Care)</h1>
    <a href="/anc" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg shadow-sm hover:bg-gray-300">
        <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>Kembali
    </a>
</div>

<form action="/anc/store" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

    <!-- Data Pasien & Kunjungan -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-indigo-50">
            <i data-lucide="user" class="inline h-5 w-5 mr-2"></i>Data Pasien & Kunjungan
        </div>
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
                <label for="kunjungan_ke" class="block text-sm font-medium text-gray-700">Kunjungan Ke- <span class="text-red-500">*</span></label>
                <select id="kunjungan_ke" name="kunjungan_ke" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Pilih --</option>
                    <option value="K1">K1 (Trimester 1)</option>
                    <option value="K2">K2</option>
                    <option value="K3">K3</option>
                    <option value="K4">K4 (Trimester 2)</option>
                    <option value="K5">K5</option>
                    <option value="K6">K6 (Trimester 3)</option>
                </select>
            </div>
            <div class="md:col-span-4">
                <label for="usia_kehamilan" class="block text-sm font-medium text-gray-700">Usia Kehamilan (Minggu) <span class="text-red-500">*</span></label>
                <input type="number" id="usia_kehamilan" name="usia_kehamilan" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Akan terisi otomatis">
            </div>
        </div>
    </div>

    <!-- Patient History Cards -->
    <div id="patient-info-cards" class="hidden bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-blue-50">
            <i data-lucide="file-text" class="inline h-5 w-5 mr-2"></i>Riwayat Kesehatan Pasien
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Basic Info Card -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                    <i data-lucide="user-circle" class="h-4 w-4 mr-2"></i>Informasi Dasar
                </h4>
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
                <h4 class="font-semibold text-green-800 mb-2 flex items-center">
                    <i data-lucide="baby" class="h-4 w-4 mr-2"></i>Status Kehamilan
                </h4>
                <div class="space-y-1 text-sm">
                    <p><strong>GPA:</strong> <span id="card-gpa"></span></p>
                    <p><strong>HPHT:</strong> <span id="card-hpht"></span></p>
                    <p><strong>Nama Suami:</strong> <span id="card-suami"></span></p>
                    <p><strong>Gol. Darah:</strong> <span id="card-gol-darah"></span></p>
                </div>
            </div>

            <!-- Medical History Card -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <h4 class="font-semibold text-red-800 mb-2 flex items-center">
                    <i data-lucide="alert-circle" class="h-4 w-4 mr-2"></i>Riwayat Kesehatan
                </h4>
                <div class="space-y-1 text-sm">
                    <p><strong>Riwayat Penyakit:</strong> <span id="card-riwayat-penyakit"></span></p>
                    <p><strong>Hipertensi:</strong> <span id="card-hipertensi"></span></p>
                    <p><strong>Diabetes:</strong> <span id="card-diabetes"></span></p>
                    <p><strong>Alergi Obat:</strong> <span id="card-alergi"></span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- PEMERIKSAAN 10T SESUAI STANDAR KEMENKES RI -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-green-50">
            <i data-lucide="clipboard-check" class="inline h-5 w-5 mr-2"></i>Pemeriksaan 10T (Standar Kemenkes RI)
        </div>
        <div class="p-6 space-y-6">
            
            <!-- S: Subjective -->
            <div class="border-l-4 border-blue-500 pl-4">
                <label for="keluhan" class="block text-sm font-bold text-gray-700 mb-2">S: Keluhan (Subjective)</label>
                <textarea id="keluhan" name="keluhan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Keluhan yang dirasakan ibu hamil..."></textarea>
            </div>
            
            <hr class="my-6">
            
            <!-- O: Objective - Pemeriksaan 10T -->
            <div class="border-l-4 border-green-500 pl-4">
                <h5 class="block text-sm font-bold text-gray-700 mb-4">O: Pemeriksaan (Objective) - 10T</h5>
                
                <!-- T1: Timbang Berat Badan & Ukur Tinggi Badan -->
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h6 class="font-semibold text-gray-700 mb-3">T1 & T2: Timbang BB & Ukur TB</h6>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs text-gray-600">Berat Badan (Kg) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.1" name="berat_badan" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="58.5">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600">Tinggi Badan (Cm)</label>
                            <input type="number" name="tinggi_badan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="160">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600">LILA (Cm) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.1" name="lila" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="23.5">
                            <p class="text-xs text-gray-500 mt-1">KEK jika <23.5 cm</p>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600">Tekanan Darah (mmHg) <span class="text-red-500">*</span></label>
                            <input type="text" name="tekanan_darah" required placeholder="120/80" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                    </div>
                </div>

                <!-- T3: Ukur Tinggi Fundus Uteri -->
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h6 class="font-semibold text-gray-700 mb-3">T3: Ukur Tinggi Fundus Uteri & Pemeriksaan Janin</h6>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs text-gray-600">TFU (Cm)</label>
                            <input type="number" step="0.1" name="tfu" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="28.5">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600">DJJ (x/menit)</label>
                            <input type="number" name="djj" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="140">
                            <p class="text-xs text-gray-500 mt-1">Normal: 120-160</p>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-600">Presentasi Janin</label>
                            <select name="presentasi_janin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Pilih --</option>
                                <option value="Kepala">Kepala</option>
                                <option value="Sungsang">Sungsang</option>
                                <option value="Lintang">Lintang</option>
                                <option value="Belum Jelas">Belum Jelas</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- T4: Pemberian Tablet Fe -->
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h6 class="font-semibold text-gray-700 mb-3">T4: Pemberian Tablet Fe (Tablet Tambah Darah)</h6>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs text-gray-600">Tablet Fe Diberikan</label>
                            <select name="tablet_fe" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Pilih --</option>
                                <option value="Ya, 30 tablet">Ya, 30 tablet</option>
                                <option value="Ya, 60 tablet">Ya, 60 tablet</option>
                                <option value="Ya, 90 tablet">Ya, 90 tablet</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600">Kadar Hb (g/dL)</label>
                            <input type="number" step="0.1" name="hb_hemoglobin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="11.5">
                            <p class="text-xs text-gray-500 mt-1">Anemia jika <11 g/dL</p>
                        </div>
                    </div>
                </div>

                <!-- T5: Imunisasi TT -->
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h6 class="font-semibold text-gray-700 mb-3">T5: Imunisasi TT (Tetanus Toksoid)</h6>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs text-gray-600">Status Imunisasi TT</label>
                            <select name="imunisasi_tt" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Pilih --</option>
                                <option value="TT1">TT1 (Pertama)</option>
                                <option value="TT2">TT2 (Kedua)</option>
                                <option value="TT3">TT3 (Ketiga)</option>
                                <option value="TT4">TT4 (Keempat)</option>
                                <option value="TT5">TT5 (Kelima/Lengkap)</option>
                                <option value="Sudah Lengkap">Sudah Lengkap</option>
                                <option value="Belum">Belum</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- T6: Pemeriksaan Laboratorium -->
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h6 class="font-semibold text-gray-700 mb-3">T6: Pemeriksaan Laboratorium</h6>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-3">
                        <div>
                            <label class="block text-xs text-gray-600">Protein Urin</label>
                            <select name="protein_urin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Pilih --</option>
                                <option value="Negatif">Negatif (-)</option>
                                <option value="Positif 1">Positif 1 (+)</option>
                                <option value="Positif 2">Positif 2 (++)</option>
                                <option value="Positif 3">Positif 3 (+++)</option>
                                <option value="Positif 4">Positif 4 (++++)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600">Tes HIV</label>
                            <select name="hiv_test" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Pilih --</option>
                                <option value="Non-Reaktif">Non-Reaktif</option>
                                <option value="Reaktif">Reaktif</option>
                                <option value="Belum Diperiksa">Belum Diperiksa</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600">Tes Sifilis</label>
                            <select name="sifilis_test" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Pilih --</option>
                                <option value="Non-Reaktif">Non-Reaktif</option>
                                <option value="Reaktif">Reaktif</option>
                                <option value="Belum Diperiksa">Belum Diperiksa</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600">Tes Hepatitis B</label>
                            <select name="hepatitis_b_test" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Pilih --</option>
                                <option value="Non-Reaktif">Non-Reaktif</option>
                                <option value="Reaktif">Reaktif</option>
                                <option value="Belum Diperiksa">Belum Diperiksa</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600">Gol. Darah Suami</label>
                            <select name="golongan_darah_suami" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Pilih --</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600">Hasil Lab Lainnya</label>
                        <textarea name="hasil_lab" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Hasil pemeriksaan laboratorium lainnya..."></textarea>
                    </div>
                </div>

                <!-- T7-T10: Temu Wicara & Tatalaksana -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h6 class="font-semibold text-gray-700 mb-3">T7-T10: Temu Wicara, Tatalaksana & Deteksi Risiko</h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-600">Skor Poedji Rochjati (SPR)</label>
                            <input type="number" name="skor_poedji_rochjati" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="2">
                            <p class="text-xs text-gray-500 mt-1">Minimal 2 (KRR)</p>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600">Kategori Risiko</label>
                            <select name="kategori_risiko" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Pilih --</option>
                                <option value="Rendah">KRR - Kehamilan Risiko Rendah (Skor 2)</option>
                                <option value="Tinggi">KRT - Kehamilan Risiko Tinggi (Skor 6-10)</option>
                                <option value="Sangat Tinggi">KRST - Kehamilan Risiko Sangat Tinggi (Skor ≥12)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-6">

            <!-- A: Assessment -->
            <div class="border-l-4 border-yellow-500 pl-4">
                <label for="diagnosis" class="block text-sm font-bold text-gray-700 mb-2">A: Diagnosis (Assessment)</label>
                <textarea id="diagnosis" name="diagnosis" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Diagnosis kehamilan dan kondisi ibu..."></textarea>
            </div>
            
            <hr class="my-6">

            <!-- P: Planning -->
            <div class="border-l-4 border-purple-500 pl-4">
                <h5 class="block text-sm font-bold text-gray-700 mb-4">P: Perencanaan (Planning)</h5>
                <div class="space-y-4">
                    <div>
                        <label for="terapi" class="text-sm font-medium text-gray-700">Terapi / Obat yang Diberikan</label>
                        <textarea id="terapi" name="terapi" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: Tablet Fe 1x1, Vitamin B Complex 1x1, Kalsium 1x1"></textarea>
                    </div>
                    <div>
                        <label for="konseling" class="text-sm font-medium text-gray-700">Edukasi / Konseling (T7: Temu Wicara)</label>
                        <textarea id="konseling" name="konseling" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: Konseling gizi, tanda bahaya kehamilan, persiapan persalinan, KB pasca salin, ASI eksklusif, dll."></textarea>
                    </div>
                    <div class="max-w-xs">
                        <label for="jadwal_kunjungan_ulang" class="text-sm font-medium text-gray-700">Jadwal Kunjungan Ulang</label>
                        <input type="date" id="jadwal_kunjungan_ulang" name="jadwal_kunjungan_ulang" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Submit Button -->
    <div class="mt-6">
        <button type="submit" class="w-full inline-flex justify-center items-center py-3 px-4 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i data-lucide="save" class="mr-2 h-5 w-5"></i>
            Simpan Pemeriksaan ANC
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
