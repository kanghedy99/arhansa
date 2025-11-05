<?php 
// File: views/nifas/create.php - Sesuai Standar Pelayanan Nifas Kemenkes RI
include __DIR__ . '/../layouts/partials/header.php'; 
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Formulir Pemeriksaan Nifas (Post Partum Care)</h1>
    <a href="/nifas" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg shadow-sm hover:bg-gray-300">
        <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>Kembali
    </a>
</div>

<form action="/nifas/store" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
    
    <!-- Data Pasien & Kunjungan -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-purple-50">
            <i data-lucide="user" class="inline h-5 w-5 mr-2"></i>Data Pasien & Kunjungan Nifas
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="md:col-span-2">
                <label for="pasien_id" class="block text-sm font-medium text-gray-700">Pilih Pasien (Ibu) <span class="text-red-500">*</span></label>
                <select id="pasien_id" name="pasien_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Cari Pasien --</option>
                    <?php foreach ($pasiens as $pasien): ?>
                        <option value="<?php echo e($pasien['id']); ?>"><?php echo e($pasien['no_rm']); ?> - <?php echo e($pasien['nama_pasien']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="tanggal_kunjungan" class="block text-sm font-medium text-gray-700">Tgl Kunjungan <span class="text-red-500">*</span></label>
                <input type="date" id="tanggal_kunjungan" name="tanggal_kunjungan" value="<?php echo date('Y-m-d'); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label for="hari_ke" class="block text-sm font-medium text-gray-700">Hari Ke- <span class="text-red-500">*</span></label>
                <input type="number" id="hari_ke" name="hari_ke" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Akan terisi otomatis">
            </div>
            <div class="md:col-span-4">
                <label for="kunjungan_ke" class="block text-sm font-medium text-gray-700">Kunjungan Nifas Ke- <span class="text-red-500">*</span></label>
                <select id="kunjungan_ke" name="kunjungan_ke" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Pilih --</option>
                    <option value="KF1">KF1 (6 jam - 3 hari)</option>
                    <option value="KF2">KF2 (4 - 28 hari)</option>
                    <option value="KF3">KF3 (29 - 42 hari)</option>
                    <option value="KF4">KF4 (Setelah 42 hari)</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">KF1: 6 jam-3 hari | KF2: 4-28 hari | KF3: 29-42 hari | KF4: >42 hari</p>
            </div>
        </div>
    </div>

    <!-- Patient History Cards -->
    <div id="patient-info-cards" class="hidden bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-blue-50">
            <i data-lucide="file-text" class="inline h-5 w-5 mr-2"></i>Riwayat Kesehatan Pasien
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h5 class="font-semibold text-blue-800 mb-2">Informasi Dasar</h5>
                <div class="space-y-1 text-sm">
                    <p><strong>Nama:</strong> <span id="card-nama"></span></p>
                    <p><strong>No. RM:</strong> <span id="card-no-rm"></span></p>
                    <p><strong>Tanggal Lahir:</strong> <span id="card-tanggal-lahir"></span></p>
                    <p><strong>Alamat:</strong> <span id="card-alamat"></span></p>
                    <p><strong>Telepon:</strong> <span id="card-telepon"></span></p>
                </div>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h5 class="font-semibold text-green-800 mb-2">Status Kehamilan</h5>
                <div class="space-y-1 text-sm">
                    <p><strong>GPA:</strong> <span id="card-gpa"></span></p>
                    <p><strong>HPHT:</strong> <span id="card-hpht"></span></p>
                    <p><strong>Nama Suami:</strong> <span id="card-suami"></span></p>
                    <p><strong>Gol. Darah:</strong> <span id="card-gol-darah"></span></p>
                </div>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <h5 class="font-semibold text-red-800 mb-2">Riwayat Kesehatan</h5>
                <div class="space-y-1 text-sm">
                    <p><strong>Riwayat Penyakit:</strong> <span id="card-riwayat-penyakit"></span></p>
                    <p><strong>Hipertensi:</strong> <span id="card-hipertensi"></span></p>
                    <p><strong>Diabetes:</strong> <span id="card-diabetes"></span></p>
                    <p><strong>Alergi Obat:</strong> <span id="card-alergi"></span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tanda-tanda Vital -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-green-50">
            <i data-lucide="activity" class="inline h-5 w-5 mr-2"></i>Tanda-tanda Vital
        </div>
        <div class="p-6 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tekanan Darah <span class="text-red-500">*</span></label>
                <input type="text" name="tekanan_darah" required placeholder="120/80" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Suhu (°C) <span class="text-red-500">*</span></label>
                <input type="number" step="0.1" name="suhu" required placeholder="36.5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nadi (x/menit) <span class="text-red-500">*</span></label>
                <input type="number" name="nadi" required placeholder="80" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Pernapasan (x/menit)</label>
                <input type="number" name="pernapasan" placeholder="20" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
        </div>
    </div>

    <!-- Pemeriksaan Fisik Nifas -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-yellow-50">
            <i data-lucide="clipboard-check" class="inline h-5 w-5 mr-2"></i>Pemeriksaan Fisik Masa Nifas
        </div>
        <div class="p-6 space-y-6">
            
            <!-- Pemeriksaan Uterus -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <h6 class="font-semibold text-gray-700 mb-3">Pemeriksaan Uterus</h6>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">TFU (Tinggi Fundus Uteri) <span class="text-red-500">*</span></label>
                        <select name="tfu" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Setinggi Pusat">Setinggi Pusat</option>
                            <option value="2 Jari di Bawah Pusat">2 Jari di Bawah Pusat</option>
                            <option value="Pertengahan Pusat-Simfisis">Pertengahan Pusat-Simfisis</option>
                            <option value="Di Atas Simfisis">Di Atas Simfisis</option>
                            <option value="Tidak Teraba">Tidak Teraba (Normal >2 minggu)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kontraksi Uterus <span class="text-red-500">*</span></label>
                        <select name="kontraksi_uterus" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Baik">Baik (Keras, Bundar)</option>
                            <option value="Lembek">Lembek (Atonia)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Involusi Uterus <span class="text-red-500">*</span></label>
                        <select name="involusi_uterus" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Sesuai">Sesuai (Normal)</option>
                            <option value="Tidak Sesuai">Tidak Sesuai (Subinvolusi)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Lokia -->
            <div class="bg-red-50 p-4 rounded-lg">
                <h6 class="font-semibold text-gray-700 mb-3">Lokia (Darah Nifas)</h6>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Lokia <span class="text-red-500">*</span></label>
                        <select name="lokia" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Rubra">Rubra (Merah, 1-3 hari)</option>
                            <option value="Sanguinolenta">Sanguinolenta (Merah Kecoklatan, 3-7 hari)</option>
                            <option value="Serosa">Serosa (Kuning Kecoklatan, 7-14 hari)</option>
                            <option value="Alba">Alba (Putih/Kuning, >14 hari)</option>
                            <option value="Berbau">Berbau (Tanda Infeksi)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                        <select name="jumlah_lokia" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Sedikit">Sedikit (Normal)</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Banyak">Banyak (Perdarahan)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Kondisi Perineum -->
            <div class="bg-orange-50 p-4 rounded-lg">
                <h6 class="font-semibold text-gray-700 mb-3">Kondisi Perineum / Luka Jahitan</h6>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kondisi Perineum</label>
                        <select name="kondisi_perineum_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Utuh">Utuh (Tidak Ada Luka)</option>
                            <option value="Jahitan Baik">Jahitan Baik (Kering, Tidak Bengkak)</option>
                            <option value="Jahitan Basah">Jahitan Basah</option>
                            <option value="Bengkak">Bengkak/Edema</option>
                            <option value="Kemerahan">Kemerahan (Tanda Infeksi)</option>
                            <option value="Bernanah">Bernanah (Infeksi)</option>
                            <option value="Jahitan Lepas">Jahitan Lepas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Keterangan Detail</label>
                        <textarea name="kondisi_perineum" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Deskripsi detail kondisi perineum..."></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pemeriksaan Payudara & Laktasi -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-pink-50">
            <i data-lucide="heart" class="inline h-5 w-5 mr-2"></i>Pemeriksaan Payudara & Laktasi
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Produksi ASI <span class="text-red-500">*</span></label>
                    <select name="asi" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Pilih --</option>
                        <option value="Lancar">Lancar</option>
                        <option value="Tidak Lancar">Tidak Lancar</option>
                        <option value="Belum Keluar">Belum Keluar</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status Menyusui <span class="text-red-500">*</span></label>
                    <select name="laktasi" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Pilih --</option>
                        <option value="Ya">Ya, Menyusui</option>
                        <option value="Tidak">Tidak Menyusui</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Pemeriksaan Payudara</label>
                <textarea name="pemeriksaan_payudara" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Kondisi payudara, puting, bengkak, nyeri, dll..."></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Masalah Laktasi (jika ada)</label>
                <textarea name="masalah_laktasi" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Puting lecet, payudara bengkak, mastitis, dll..."></textarea>
            </div>
        </div>
    </div>

    <!-- Tanda Bahaya Nifas -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-red-50">
            <i data-lucide="alert-triangle" class="inline h-5 w-5 mr-2"></i>Tanda Bahaya Nifas
        </div>
        <div class="p-6">
            <div class="bg-red-50 p-4 rounded-lg mb-4">
                <p class="text-sm text-gray-700 mb-2"><strong>Tanda Bahaya yang Perlu Diwaspadai:</strong></p>
                <ul class="text-xs text-gray-600 list-disc list-inside space-y-1">
                    <li>Perdarahan >500 ml atau terus menerus</li>
                    <li>Demam >38°C</li>
                    <li>Nyeri perut hebat</li>
                    <li>Lokia berbau busuk</li>
                    <li>Payudara bengkak merah dan nyeri</li>
                    <li>Luka jahitan bernanah</li>
                    <li>Sakit kepala hebat, pandangan kabur</li>
                    <li>Sesak napas</li>
                </ul>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanda Bahaya yang Ditemukan</label>
                <textarea name="tanda_bahaya" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Sebutkan tanda bahaya jika ada, atau tulis 'Tidak Ada'"></textarea>
            </div>
        </div>
    </div>

    <!-- Keluhan & Tindakan -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-indigo-50">
            <i data-lucide="stethoscope" class="inline h-5 w-5 mr-2"></i>Keluhan & Tindakan
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label for="keluhan" class="block text-sm font-medium text-gray-700">Keluhan Ibu</label>
                <textarea id="keluhan" name="keluhan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Keluhan yang dirasakan ibu nifas..."></textarea>
            </div>
            <div>
                <label for="tindakan_konseling" class="block text-sm font-medium text-gray-700">Tindakan & Konseling yang Diberikan</label>
                <textarea id="tindakan_konseling" name="tindakan_konseling" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Tindakan medis, edukasi perawatan nifas, perawatan payudara, nutrisi, dll..."></textarea>
            </div>
        </div>
    </div>

    <!-- Pemberian Suplemen & Konseling KB -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-green-50">
            <i data-lucide="pill" class="inline h-5 w-5 mr-2"></i>Pemberian Suplemen & Konseling KB
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Vitamin A (200.000 IU) <span class="text-red-500">*</span></label>
                    <select name="vitamin_a" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Pilih --</option>
                        <option value="Sudah">Sudah Diberikan</option>
                        <option value="Belum">Belum Diberikan</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Diberikan 2 kapsul: 1 kapsul segera setelah melahirkan, 1 kapsul 24 jam kemudian</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tablet Fe (Zat Besi) <span class="text-red-500">*</span></label>
                    <select name="tablet_fe" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Pilih --</option>
                        <option value="Sudah">Sudah Diberikan</option>
                        <option value="Belum">Belum Diberikan</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Diberikan selama 40 hari masa nifas</p>
                </div>
            </div>
            <div>
                <label for="konseling_kb" class="block text-sm font-medium text-gray-700">Konseling KB Pasca Salin</label>
                <textarea id="konseling_kb" name="konseling_kb" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Konseling tentang metode KB, waktu mulai KB, pilihan metode KB, dll..."></textarea>
                <p class="text-xs text-gray-500 mt-1">KB dapat dimulai setelah 40 hari atau sesuai kondisi ibu</p>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="mt-6">
        <button type="submit" class="w-full inline-flex justify-center items-center py-3 px-4 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
            <i data-lucide="save" class="mr-2 h-5 w-5"></i>
            Simpan Kunjungan Nifas
        </button>
    </div>
</form>

<script>
document.getElementById('pasien_id').addEventListener('change', function() {
    const pasienId = this.value;
    if (pasienId) {
        fetch(`/nifas/get_patient_data?pasien_id=${pasienId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show cards
                    document.getElementById('patient-info-cards').style.display = 'block';

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

                    // Auto-fill hari_ke (postpartum days)
                    if (data.hari_ke !== null && data.hari_ke > 0) {
                        document.getElementById('hari_ke').value = data.hari_ke;
                        
                        // Auto-select kunjungan_ke based on hari_ke
                        const hariKe = data.hari_ke;
                        const kunjunganSelect = document.getElementById('kunjungan_ke');
                        if (hariKe <= 3) {
                            kunjunganSelect.value = 'KF1';
                        } else if (hariKe <= 28) {
                            kunjunganSelect.value = 'KF2';
                        } else if (hariKe <= 42) {
                            kunjunganSelect.value = 'KF3';
                        } else {
                            kunjunganSelect.value = 'KF4';
                        }
                    } else {
                        document.getElementById('hari_ke').value = '';
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
        document.getElementById('patient-info-cards').style.display = 'none';
        document.getElementById('hari_ke').value = '';
    }
});
</script>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>
