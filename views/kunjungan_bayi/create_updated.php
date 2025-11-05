<?php 
// File: views/kunjungan_bayi/create.php - Sesuai Standar Pemantauan Tumbuh Kembang Kemenkes RI
include __DIR__ . '/../layouts/partials/header.php'; 
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Formulir Kunjungan Bayi & Pemantauan Tumbuh Kembang</h1>
    <a href="/kunjungan_bayi" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg shadow-sm hover:bg-gray-300">
        <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>Kembali
    </a>
</div>

<form action="/kunjungan_bayi/store" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

    <!-- Data Bayi & Kunjungan -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-blue-50">
            <i data-lucide="baby" class="inline h-5 w-5 mr-2"></i>Data Bayi & Jenis Kunjungan
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="md:col-span-2">
                <label for="bayi_id" class="block text-sm font-medium text-gray-700">Pilih Bayi <span class="text-red-500">*</span></label>
                <select id="bayi_id" name="bayi_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Pilih Bayi (Nama Ibu) --</option>
                    <?php foreach ($bayis as $bayi): ?>
                        <option value="<?php echo e($bayi['id']); ?>" 
                                data-tanggal-lahir="<?php echo e($bayi['tanggal_lahir']); ?>">
                            <?php echo e($bayi['nama_bayi']); ?> (Ibu: <?php echo e($bayi['nama_ibu']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="tanggal_kunjungan" class="block text-sm font-medium text-gray-700">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                <input type="date" id="tanggal_kunjungan" name="tanggal_kunjungan" value="<?php echo date('Y-m-d'); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label for="usia_hari" class="block text-sm font-medium text-gray-700">Usia (Hari)</label>
                <input type="number" id="usia_hari" name="usia_hari" readonly class="mt-1 block w-full rounded-md border-gray-200 bg-gray-50 shadow-sm">
            </div>
            <div>
                <label for="usia_bulan" class="block text-sm font-medium text-gray-700">Usia (Bulan)</label>
                <input type="number" id="usia_bulan" name="usia_bulan" readonly class="mt-1 block w-full rounded-md border-gray-200 bg-gray-50 shadow-sm">
            </div>
            <div class="md:col-span-3">
                <label for="kunjungan_ke" class="block text-sm font-medium text-gray-700">Jenis Kunjungan <span class="text-red-500">*</span></label>
                <select id="kunjungan_ke" name="kunjungan_ke" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Pilih --</option>
                    <option value="KN1">KN1 (6-48 jam)</option>
                    <option value="KN2">KN2 (3-7 hari)</option>
                    <option value="KN3">KN3 (8-28 hari)</option>
                    <option value="Lainnya">Kunjungan Rutin (>28 hari)</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">KN1: 6-48 jam | KN2: 3-7 hari | KN3: 8-28 hari | Lainnya: >28 hari</p>
            </div>
        </div>
    </div>

    <!-- Antropometri & Tanda Vital -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-green-50">
            <i data-lucide="ruler" class="inline h-5 w-5 mr-2"></i>Antropometri & Tanda Vital
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Berat Badan (Kg) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="berat_badan" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="5.5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Panjang Badan (Cm) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.1" name="panjang_badan" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="55.5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lingkar Kepala (Cm) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.1" name="lingkar_kepala" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="38.5">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Suhu Tubuh (°C)</label>
                    <input type="number" step="0.1" name="suhu_tubuh" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="36.5">
                </div>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg">
                <h6 class="font-semibold text-gray-700 mb-2">Status Gizi (Akan dihitung otomatis)</h6>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs text-gray-600">BB/U (Berat/Usia)</label>
                        <input type="text" id="status_bb_u" readonly class="mt-1 block w-full rounded-md border-gray-200 bg-white shadow-sm text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600">PB/U (Panjang/Usia)</label>
                        <input type="text" id="status_pb_u" readonly class="mt-1 block w-full rounded-md border-gray-200 bg-white shadow-sm text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600">BB/PB (Berat/Panjang)</label>
                        <input type="text" id="status_bb_pb" readonly class="mt-1 block w-full rounded-md border-gray-200 bg-white shadow-sm text-sm">
                    </div>
                </div>
                <input type="hidden" name="status_gizi" id="status_gizi_hidden">
            </div>
        </div>
    </div>

    <!-- ASI Eksklusif & Nutrisi -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-pink-50">
            <i data-lucide="milk" class="inline h-5 w-5 mr-2"></i>ASI Eksklusif & Nutrisi
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">ASI Eksklusif <span class="text-red-500">*</span></label>
                    <select name="asi_eksklusif" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Pilih --</option>
                        <option value="Ya">Ya, ASI Eksklusif</option>
                        <option value="Tidak">Tidak, Sudah Diberi Makanan Lain</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">ASI Eksklusif: 0-6 bulan hanya ASI tanpa makanan/minuman lain</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pemberian Vitamin A</label>
                    <select name="vitamin_a" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Pilih --</option>
                        <option value="Sudah">Sudah Diberikan</option>
                        <option value="Belum">Belum Diberikan</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Vitamin A: 6 bulan (Biru 100.000 IU), 12 bulan (Merah 200.000 IU)</p>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Pola Makan & Nutrisi</label>
                <textarea name="pola_makan" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Frekuensi menyusu, MPASI (jika sudah), dll..."></textarea>
            </div>
        </div>
    </div>

    <!-- Imunisasi -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-purple-50">
            <i data-lucide="syringe" class="inline h-5 w-5 mr-2"></i>Imunisasi pada Kunjungan Ini
        </div>
        <div class="p-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Jenis Imunisasi yang Diberikan</label>
                <select name="jenis_imunisasi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Pilih (Jika Ada) --</option>
                    <option value="Hepatitis B (HB-0)">Hepatitis B (HB-0) - 0-24 jam</option>
                    <option value="BCG">BCG - 1 bulan</option>
                    <option value="Polio 1 (OPV 1)">Polio 1 (OPV 1) - 1 bulan</option>
                    <option value="DPT-HB-Hib 1">DPT-HB-Hib 1 - 2 bulan</option>
                    <option value="Polio 2 (OPV 2)">Polio 2 (OPV 2) - 2 bulan</option>
                    <option value="DPT-HB-Hib 2">DPT-HB-Hib 2 - 3 bulan</option>
                    <option value="Polio 3 (OPV 3)">Polio 3 (OPV 3) - 3 bulan</option>
                    <option value="DPT-HB-Hib 3">DPT-HB-Hib 3 - 4 bulan</option>
                    <option value="Polio 4 (OPV 4)">Polio 4 (OPV 4) - 4 bulan</option>
                    <option value="IPV (Polio Suntik)">IPV (Polio Suntik) - 4 bulan</option>
                    <option value="Campak/MR 1">Campak/MR 1 - 9 bulan</option>
                    <option value="Tidak Ada">Tidak Ada Imunisasi</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Pilih imunisasi yang diberikan pada kunjungan ini (jika ada)</p>
            </div>
        </div>
    </div>

    <!-- Tanda Bahaya Bayi -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-red-50">
            <i data-lucide="alert-triangle" class="inline h-5 w-5 mr-2"></i>Tanda Bahaya pada Bayi
        </div>
        <div class="p-6">
            <div class="bg-red-50 p-4 rounded-lg mb-4">
                <p class="text-sm font-semibold text-gray-700 mb-2">Tanda Bahaya yang Perlu Diwaspadai:</p>
                <ul class="text-xs text-gray-600 list-disc list-inside space-y-1">
                    <li>Tidak mau menyusu atau memuntahkan semua</li>
                    <li>Kejang</li>
                    <li>Lemah, mengantuk terus, atau tidak sadar</li>
                    <li>Sesak napas (>60x/menit atau <30x/menit)</li>
                    <li>Demam (>37.5°C) atau tubuh teraba dingin (<36.5°C)</li>
                    <li>Tali pusat merah, bengkak, keluar nanah, atau berbau</li>
                    <li>Diare</li>
                    <li>Mata bernanah</li>
                    <li>Kulit kuning (terutama telapak tangan/kaki)</li>
                    <li>Berat badan tidak naik 2 bulan berturut-turut</li>
                </ul>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanda Bahaya yang Ditemukan</label>
                <textarea name="tanda_bahaya" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Sebutkan tanda bahaya jika ada, atau tulis 'Tidak Ada'"></textarea>
            </div>
        </div>
    </div>

    <!-- Perkembangan Bayi -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-yellow-50">
            <i data-lucide="smile" class="inline h-5 w-5 mr-2"></i>Perkembangan Motorik & Sosial
        </div>
        <div class="p-6 space-y-4">
            <div class="bg-blue-50 p-4 rounded-lg">
                <h6 class="font-semibold text-gray-700 mb-2">Milestone Perkembangan Sesuai Usia</h6>
                <div class="text-xs text-gray-600 space-y-1">
                    <p><strong>0-3 bulan:</strong> Mengangkat kepala, tersenyum, mengikuti objek dengan mata</p>
                    <p><strong>4-6 bulan:</strong> Tengkurap, duduk dengan bantuan, meraih mainan, tertawa</p>
                    <p><strong>7-9 bulan:</strong> Duduk sendiri, merangkak, memindahkan benda, mengoceh</p>
                    <p><strong>10-12 bulan:</strong> Berdiri dengan pegangan, berjalan dengan bantuan, mengatakan 1-2 kata</p>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Perkembangan yang Dicapai</label>
                <textarea name="perkembangan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Catat perkembangan motorik kasar, motorik halus, bahasa, dan sosial yang sudah dicapai..."></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Skrining Tumbuh Kembang (KPSP)</label>
                <textarea name="skrining_tumbuh_kembang" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Hasil skrining KPSP jika dilakukan..."></textarea>
                <p class="text-xs text-gray-500 mt-1">KPSP: Kuesioner Pra Skrining Perkembangan</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Stimulasi yang Diberikan</label>
                <textarea name="stimulasi" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Edukasi stimulasi yang diberikan kepada orang tua..."></textarea>
            </div>
        </div>
    </div>

    <!-- Catatan Klinis & Tindakan -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-indigo-50">
            <i data-lucide="clipboard" class="inline h-5 w-5 mr-2"></i>Catatan Klinis & Tindakan
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label for="catatan_klinis" class="block text-sm font-medium text-gray-700">Catatan Klinis</label>
                <textarea id="catatan_klinis" name="catatan_klinis" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Kondisi umum bayi, keluhan, pemeriksaan fisik, dll..."></textarea>
            </div>
            <div>
                <label for="tindakan" class="block text-sm font-medium text-gray-700">Tindakan & Edukasi</label>
                <textarea id="tindakan" name="tindakan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Tindakan medis, edukasi perawatan bayi, nutrisi, stimulasi, dll..."></textarea>
            </div>
            <div>
                <label for="pemberi_layanan" class="block text-sm font-medium text-gray-700">Pemberi Layanan</label>
                <input type="text" id="pemberi_layanan" name="pemberi_layanan" value="<?php echo e($_SESSION['user_name']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="mt-6">
        <button type="submit" class="w-full inline-flex justify-center items-center py-3 px-4 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
            <i data-lucide="save" class="mr-2 h-5 w-5"></i>
            Simpan Kunjungan Bayi
        </button>
    </div>
</form>

<script>
// Calculate age when baby is selected
document.getElementById('bayi_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const tanggalLahir = selectedOption.getAttribute('data-tanggal-lahir');
    
    if (tanggalLahir) {
        calculateAge(tanggalLahir);
    }
});

// Calculate age when visit date changes
document.getElementById('tanggal_kunjungan').addEventListener('change', function() {
    const bayi = document.getElementById('bayi_id');
    const selectedOption = bayi.options[bayi.selectedIndex];
    const tanggalLahir = selectedOption.getAttribute('data-tanggal-lahir');
    
    if (tanggalLahir) {
        calculateAge(tanggalLahir);
    }
});

function calculateAge(tanggalLahir) {
    const birthDate = new Date(tanggalLahir);
    const visitDate = new Date(document.getElementById('tanggal_kunjungan').value);
    
    const diffTime = Math.abs(visitDate - birthDate);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    const diffMonths = Math.floor(diffDays / 30);
    
    document.getElementById('usia_hari').value = diffDays;
    document.getElementById('usia_bulan').value = diffMonths;
    
    // Auto-select kunjungan_ke based on age
    const kunjunganSelect = document.getElementById('kunjungan_ke');
    if (diffDays <= 2) {
        kunjunganSelect.value = 'KN1';
    } else if (diffDays <= 7) {
        kunjunganSelect.value = 'KN2';
    } else if (diffDays <= 28) {
        kunjunganSelect.value = 'KN3';
    } else {
        kunjunganSelect.value = 'Lainnya';
    }
}

// Simple status gizi calculation (simplified version)
document.querySelector('input[name="berat_badan"]').addEventListener('input', calculateStatusGizi);
document.querySelector('input[name="panjang_badan"]').addEventListener('input', calculateStatusGizi);

function calculateStatusGizi() {
    const bb = parseFloat(document.querySelector('input[name="berat_badan"]').value);
    const pb = parseFloat(document.querySelector('input[name="panjang_badan"]').value);
    const usiaBulan = parseInt(document.getElementById('usia_bulan').value);
    
    if (bb && pb && usiaBulan >= 0) {
        // Simplified calculation - in real app, use WHO growth charts
        const bbPb = (bb / (pb/100)) * 10;
        
        let statusBBPB = '';
        if (bbPb < 13) statusBBPB = 'Gizi Buruk';
        else if (bbPb < 14) statusBBPB = 'Gizi Kurang';
        else if (bbPb < 17) statusBBPB = 'Gizi Baik';
        else statusBBPB = 'Gizi Lebih';
        
        document.getElementById('status_bb_pb').value = statusBBPB;
        document.getElementById('status_bb_u').value = 'Perlu Grafik KMS';
        document.getElementById('status_pb_u').value = 'Perlu Grafik KMS';
        document.getElementById('status_gizi_hidden').value = `BB/PB: ${statusBBPB}`;
    }
}
</script>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>
