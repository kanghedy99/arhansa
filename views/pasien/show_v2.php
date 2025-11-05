<?php 
// File: views/pasien/show.php (Lengkap dengan Tab Nifas)
include __DIR__ . '/../layouts/partials/header.php'; 
?>

<!-- Header Halaman -->
<div class="no-print flex flex-wrap justify-between items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Resume Medis Pasien</h1>
        <p class="text-gray-600 mt-1"><?php echo e($pasien['nama_pasien']); ?> (No. RM: <?php echo e($pasien['no_rm']); ?>)</p>
    </div>
    <div>
        <button onclick="window.print();" class="inline-flex items-center px-4 py-2 mr-2 bg-green-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-green-700">
            <i data-lucide="printer" class="mr-2 h-4 w-4"></i>Cetak Resume
        </button>
        <a href="/pasien" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg shadow-sm hover:bg-gray-300">
            <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>Kembali
        </a>
    </div>
</div>

<!-- KARTU STATUS KLINIS (BARU) -->
<div class="mb-6 bg-indigo-50 border-l-4 border-indigo-500 rounded-r-lg p-4 flex items-start">
    <div class="flex-shrink-0">
        <i data-lucide="info" class="h-6 w-6 text-indigo-600"></i>
    </div>
    <div class="ml-3">
        <p class="text-sm font-semibold text-indigo-800">Status Klinis Saat Ini</p>
        <p class="text-lg font-bold text-indigo-900"><?php echo e($status_klinis['status']); ?></p>
        <p class="text-sm text-gray-700"><?php echo e($status_klinis['detail']); ?></p>
    </div>
</div>

<!-- Area yang akan dicetak -->
<div id="laporan-area" x-data="{ activeTab: 'biodata' }">
    <!-- Judul Laporan untuk Cetak (Hanya tampil saat print) -->
    <!-- KEPALA SURAT (KOP) UNTUK CETAK (BARU) -->
    <div class="print-header hidden mb-4">
        <div class="text-center">
            <h2 class="text-xl font-bold"><?php echo e($pengaturan['nama_klinik']); ?></h2>
            <p class="text-sm"><?php echo e($pengaturan['alamat_klinik']); ?></p>
            <p class="text-sm">Telp: <?php echo e($pengaturan['telepon_klinik']); ?> | Email: <?php echo e($pengaturan['email_klinik']); ?></p>
        </div>
        <hr class="my-2 border-t-2 border-gray-800">
        <h3 class="text-center text-lg font-semibold mt-2">RESUME MEDIS PASIEN</h3>
    </div>

    <!-- Navigasi Tab (Tidak dicetak) -->
    <div class="mb-4 border-b border-gray-200 no-print">
        <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
            <a href="#" @click.prevent="activeTab = 'biodata'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'biodata', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'biodata' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Biodata & Klinis</a>
            <a href="#" @click.prevent="activeTab = 'anc'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'anc', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'anc' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">ANC</a>
            <a href="#" @click.prevent="activeTab = 'kelahiran'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'kelahiran', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'kelahiran' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Kelahiran</a>
            <a href="#" @click.prevent="activeTab = 'nifas'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'nifas', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'nifas' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Nifas (PNC)</a>
            <a href="#" @click.prevent="activeTab = 'kb'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'kb', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'kb' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">KB</a>
            <a href="#" @click.prevent="activeTab = 'imunisasi'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'imunisasi', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'imunisasi' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Imunisasi Anak</a>
        </nav>
    </div>

    <!-- Konten Tab -->
    <div class="space-y-6">
        <!-- Biodata Pasien & Info Klinis -->
        <div x-show="activeTab === 'biodata'" class="print-section">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-4 bg-gray-50 border-b flex items-center"><i data-lucide="user-round" class="mr-3 text-indigo-600"></i><h6 class="font-semibold text-gray-700">Biodata Pasien</h6></div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><p><strong>No. RM:</strong> <?php echo e($pasien['no_rm']); ?></p></div>
                    <div><p><strong>NIK:</strong> <?php echo e($pasien['nik'] ?? '-'); ?></p></div>
                    <div><p><strong>Nama:</strong> <?php echo e($pasien['nama_pasien']); ?></p></div>
                    <div><p><strong>Usia:</strong> <?php echo e(calculateAge($pasien['tanggal_lahir'])); ?> tahun</p></div>
                    <div><p><strong>TTL:</strong> <?php echo e($pasien['tempat_lahir']); ?>, <?php echo date('d F Y', strtotime($pasien['tanggal_lahir'])); ?></p></div>
                    <div><p><strong>Alamat:</strong> <?php echo e($pasien['alamat_lengkap']); ?></p></div>
                    <div><p><strong>No. Telepon:</strong> <?php echo e($pasien['no_telepon'] ?? '-'); ?></p></div>
                    <div><p><strong>Gol. Darah:</strong> <?php echo e($pasien['golongan_darah'] ?? '-'); ?></p></div>
                    <div><p><strong>Agama:</strong> <?php echo e($pasien['agama'] ?? '-'); ?></p></div>
                    <div><p><strong>Status Pernikahan:</strong> <?php echo e($pasien['status_pernikahan'] ?? '-'); ?></p></div>
                    <div><p><strong>Nama Suami:</strong> <?php echo e($pasien['nama_suami'] ?? '-'); ?></p></div>
                    <div><p><strong>Pekerjaan Suami:</strong> <?php echo e($pasien['pekerjaan_suami'] ?? '-'); ?></p></div>
                </div>
            </div>
            <?php if($pasien['hpht']): ?>
            <div class="mt-6 bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-4 bg-pink-50 border-b flex items-center"><i data-lucide="clipboard-heart" class="mr-3 text-pink-600"></i><h6 class="font-semibold text-gray-700">Informasi Kehamilan Saat Ini</h6></div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div><p><strong>HPHT:</strong> <?php echo date('d F Y', strtotime($pasien['hpht'])); ?></p></div>
                    <div><p><strong>HPL (Tafsiran Partus):</strong> <span class="font-bold text-green-700"><?php echo date('d F Y', strtotime(calculateHPL($pasien['hpht']))); ?></span></p></div>
                    <div><p><strong>Usia Kehamilan:</strong> <span class="font-bold"><?php echo e(calculateGestationalAgeString($pasien['hpht'])); ?></span></p></div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Riwayat ANC -->
        <div x-show="activeTab === 'anc'" class="bg-white shadow-md rounded-lg overflow-hidden print-section">
             <div class="p-4 bg-gray-50 border-b flex items-center"><i data-lucide="heart-pulse" class="mr-3 text-red-600"></i><h6 class="font-semibold text-gray-700">Riwayat Kehamilan (ANC)</h6></div>
             <div class="overflow-x-auto"><table class="w-full text-xs text-left text-gray-600"><thead class="text-xs text-gray-700 uppercase bg-gray-100"><tr><th class="px-4 py-2">Tanggal</th><th class="px-4 py-2">Usia Hamil</th><th class="px-4 py-2">Keluhan</th><th class="px-4 py-2">TD</th><th class="px-4 py-2">BB</th><th class="px-4 py-2">TFU</th><th class="px-4 py-2">DJJ</th><th class="px-4 py-2">Diagnosis</th></tr></thead><tbody><?php if (empty($riwayat_anc)): ?><tr><td colspan="8" class="p-4 text-center">Tidak ada riwayat.</td></tr><?php else: ?><?php foreach ($riwayat_anc as $anc): ?><tr class="border-b"><td class="px-4 py-2"><?php echo date('d-m-y', strtotime(e($anc['tanggal_kunjungan']))); ?></td><td class="px-4 py-2"><?php echo e($anc['usia_kehamilan']); ?> mg</td><td class="px-4 py-2"><?php echo e($anc['keluhan']); ?></td><td class="px-4 py-2"><?php echo e($anc['tekanan_darah']); ?></td><td class="px-4 py-2"><?php echo e($anc['berat_badan']); ?> kg</td><td class="px-4 py-2"><?php echo e($anc['tfu']); ?> cm</td><td class="px-4 py-2"><?php echo e($anc['djj']); ?> x/mnt</td><td class="px-4 py-2"><?php echo e($anc['diagnosis']); ?></td></tr><?php endforeach; ?><?php endif; ?></tbody></table></div>
        </div>
        
        <!-- Riwayat Kelahiran Anak -->
        <div x-show="activeTab === 'kelahiran'" class="bg-white shadow-md rounded-lg overflow-hidden print-section">
             <div class="p-4 bg-gray-50 border-b flex items-center"><i data-lucide="baby" class="mr-3 text-blue-600"></i><h6 class="font-semibold text-gray-700">Riwayat Kelahiran Anak</h6></div>
             <div class="overflow-x-auto"><table class="w-full text-xs text-left text-gray-600"><thead class="text-xs text-gray-700 uppercase bg-gray-100"><tr><th class="px-4 py-2">Nama Bayi</th><th class="px-4 py-2">Tgl Lahir</th><th class="px-4 py-2">JK</th><th class="px-4 py-2">BB/PB Lahir</th><th class="px-4 py-2">Lingkar Kepala</th><th class="px-4 py-2">Catatan</th></tr></thead><tbody><?php if (empty($riwayat_kelahiran)): ?><tr><td colspan="6" class="p-4 text-center">Tidak ada riwayat.</td></tr><?php else: ?><?php foreach ($riwayat_kelahiran as $kelahiran): ?><tr class="border-b"><td class="px-4 py-2"><?php echo e($kelahiran['nama_bayi']); ?></td><td class="px-4 py-2"><?php echo date('d-m-Y', strtotime(e($kelahiran['tanggal_lahir']))); ?> <?php echo e($kelahiran['jam_lahir']); ?></td><td class="px-4 py-2"><?php echo e($kelahiran['jenis_kelamin']); ?></td><td class="px-4 py-2"><?php echo e($kelahiran['berat_lahir']); ?> gr / <?php echo e($kelahiran['panjang_lahir']); ?> cm</td><td class="px-4 py-2"><?php echo e($kelahiran['lingkar_kepala']); ?> cm</td><td class="px-4 py-2"><?php echo e($kelahiran['catatan_kelahiran']); ?></td></tr><?php endforeach; ?><?php endif; ?></tbody></table></div>
        </div>

        <!-- Riwayat Nifas (PNC) -->
        <div x-show="activeTab === 'nifas'" class="bg-white shadow-md rounded-lg overflow-hidden print-section">
             <div class="p-4 bg-gray-50 border-b flex items-center"><i data-lucide="clipboard-check" class="mr-3 text-teal-600"></i><h6 class="font-semibold text-gray-700">Riwayat Pemeriksaan Nifas (PNC)</h6></div>
             <div class="overflow-x-auto"><table class="w-full text-xs text-left text-gray-600"><thead class="text-xs text-gray-700 uppercase bg-gray-100"><tr><th class="px-4 py-2">Tanggal</th><th class="px-4 py-2">Hari Ke-</th><th class="px-4 py-2">TD</th><th class="px-4 py-2">Suhu</th><th class="px-4 py-2">TFU</th><th class="px-4 py-2">Lokia</th><th class="px-4 py-2">Keluhan</th></tr></thead><tbody><?php if (empty($riwayat_nifas)): ?><tr><td colspan="7" class="p-4 text-center">Tidak ada riwayat.</td></tr><?php else: ?><?php foreach ($riwayat_nifas as $nifas): ?><tr class="border-b"><td class="px-4 py-2"><?php echo date('d-m-Y', strtotime(e($nifas['tanggal_kunjungan']))); ?></td><td class="px-4 py-2"><?php echo e($nifas['hari_ke']); ?></td><td class="px-4 py-2"><?php echo e($nifas['tekanan_darah']); ?></td><td class="px-4 py-2"><?php echo e($nifas['suhu']); ?> °C</td><td class="px-4 py-2"><?php echo e($nifas['tfu']); ?></td><td class="px-4 py-2"><?php echo e($nifas['lokia']); ?></td><td class="px-4 py-2"><?php echo e($nifas['keluhan']); ?></td></tr><?php endforeach; ?><?php endif; ?></tbody></table></div>
        </div>

        <!-- Riwayat KB -->
        <div x-show="activeTab === 'kb'" class="bg-white shadow-md rounded-lg overflow-hidden print-section">
             <div class="p-4 bg-gray-50 border-b flex items-center"><i data-lucide="syringe" class="mr-3 text-yellow-600"></i><h6 class="font-semibold text-gray-700">Riwayat Keluarga Berencana (KB)</h6></div>
             <div class="overflow-x-auto"><table class="w-full text-xs text-left text-gray-600"><thead class="text-xs text-gray-700 uppercase bg-gray-100"><tr><th class="px-4 py-2">Tanggal</th><th class="px-4 py-2">Jenis Layanan</th><th class="px-4 py-2">Metode KB</th><th class="px-4 py-2">Jadwal Kembali</th></tr></thead><tbody><?php if (empty($riwayat_kb)): ?><tr><td colspan="4" class="p-4 text-center">Tidak ada riwayat.</td></tr><?php else: ?><?php foreach ($riwayat_kb as $kb): ?><tr class="border-b"><td class="px-4 py-2"><?php echo date('d-m-Y', strtotime(e($kb['tanggal_layanan']))); ?></td><td class="px-4 py-2"><?php echo e($kb['jenis_layanan']); ?></td><td class="px-4 py-2"><?php echo e($kb['metode_kb']); ?></td><td class="px-4 py-2"><?php echo $kb['jadwal_kembali'] ? date('d-m-Y', strtotime(e($kb['jadwal_kembali']))) : '-'; ?></td></tr><?php endforeach; ?><?php endif; ?></tbody></table></div>
        </div>

        <!-- Riwayat Imunisasi Anak -->
        <div x-show="activeTab === 'imunisasi'" class="bg-white shadow-md rounded-lg overflow-hidden print-section">
             <div class="p-4 bg-gray-50 border-b flex items-center"><i data-lucide="shield-plus" class="mr-3 text-green-600"></i><h6 class="font-semibold text-gray-700">Riwayat Imunisasi Anak</h6></div>
             <div class="overflow-x-auto"><table class="w-full text-xs text-left text-gray-600"><thead class="text-xs text-gray-700 uppercase bg-gray-100"><tr><th class="px-4 py-2">Nama Anak</th><th class="px-4 py-2">Tgl Imunisasi</th><th class="px-4 py-2">Jenis Imunisasi</th><th class="px-4 py-2">Catatan</th></tr></thead><tbody><?php if (empty($riwayat_imunisasi_anak)): ?><tr><td colspan="4" class="p-4 text-center">Tidak ada riwayat.</td></tr><?php else: ?><?php foreach ($riwayat_imunisasi_anak as $imunisasi): ?><tr class="border-b"><td class="px-4 py-2"><?php echo e($imunisasi['nama_bayi']); ?></td><td class="px-4 py-2"><?php echo date('d-m-Y', strtotime(e($imunisasi['tanggal_imunisasi']))); ?></td><td class="px-4 py-2"><?php echo e($imunisasi['jenis_imunisasi']); ?></td><td class="px-4 py-2"><?php echo e($imunisasi['catatan']); ?></td></tr><?php endforeach; ?><?php endif; ?></tbody></table></div>
        </div>
    </div>
</div>

<!-- Alpine.js untuk fungsionalitas Tab -->
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
<!-- CSS Tambahan untuk Cetak -->
<style>
    @media print {
        body { font-size: 10pt; }
        .no-print { display: none !important; }
        .print-section { 
            display: block !important; 
            box-shadow: none !important;
            border: 1px solid #e5e7eb;
            margin-bottom: 1.5rem;
            page-break-inside: avoid;
        }
    }
</style>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>
