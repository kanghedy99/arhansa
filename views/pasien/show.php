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
    <!-- Modern Header untuk Cetak -->
    <div class="print-header hidden mb-6">
        <!-- Klinik Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-6 rounded-lg mb-4">
            <div class="text-center">
                <h1 class="text-2xl font-bold mb-2"><?php echo e($pengaturan['nama_klinik']); ?></h1>
                <div class="text-sm opacity-90">
                    <p><?php echo e($pengaturan['alamat_klinik']); ?></p>
                    <p>Telp: <?php echo e($pengaturan['telepon_klinik']); ?> | Email: <?php echo e($pengaturan['email_klinik']); ?></p>
                </div>
            </div>
        </div>

        <!-- Patient Info Card -->
        <div class="bg-white border-2 border-blue-200 rounded-lg p-4 mb-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800"><?php echo e($pasien['nama_pasien']); ?></h2>
                    <p class="text-gray-600">No. RM: <span class="font-semibold"><?php echo e($pasien['no_rm']); ?></span></p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Usia: <span class="font-semibold"><?php echo e(calculateAge($pasien['tanggal_lahir'])); ?> tahun</span></p>
                    <p class="text-sm text-gray-600">Gol. Darah: <span class="font-semibold"><?php echo e($pasien['golongan_darah'] ?? '-'); ?></span></p>
                </div>
            </div>
        </div>

        <div class="text-center mb-4">
            <h3 class="text-lg font-bold text-gray-800 border-b-2 border-blue-500 pb-2 inline-block">RESUME MEDIS PASIEN</h3>
            <p class="text-sm text-gray-600 mt-1">Tanggal Cetak: <?php echo date('d F Y'); ?></p>
        </div>
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
            <!-- Personal Information Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200 mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-4">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 rounded-full p-2 mr-3">
                            <i data-lucide="user-round" class="h-6 w-6 text-white"></i>
                        </div>
                        <h6 class="font-bold text-white text-lg">Informasi Pribadi</h6>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Basic Info -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <i data-lucide="id-card" class="h-4 w-4 mr-2 text-blue-500"></i>
                                Identitas
                            </h4>
                            <div class="space-y-2 text-sm">
                                <p><span class="font-medium text-gray-600">No. RM:</span> <span class="text-gray-800"><?php echo e($pasien['no_rm']); ?></span></p>
                                <p><span class="font-medium text-gray-600">NIK:</span> <span class="text-gray-800"><?php echo e($pasien['nik'] ?? '-'); ?></span></p>
                                <p><span class="font-medium text-gray-600">Nama:</span> <span class="text-gray-800"><?php echo e($pasien['nama_pasien']); ?></span></p>
                            </div>
                        </div>

                        <!-- Birth Info -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <i data-lucide="calendar" class="h-4 w-4 mr-2 text-green-500"></i>
                                Kelahiran
                            </h4>
                            <div class="space-y-2 text-sm">
                                <p><span class="font-medium text-gray-600">Tempat Lahir:</span> <span class="text-gray-800"><?php echo e($pasien['tempat_lahir']); ?></span></p>
                                <p><span class="font-medium text-gray-600">Tanggal Lahir:</span> <span class="text-gray-800"><?php echo date('d F Y', strtotime($pasien['tanggal_lahir'])); ?></span></p>
                                <p><span class="font-medium text-gray-600">Usia:</span> <span class="text-gray-800 font-semibold"><?php echo e(calculateAge($pasien['tanggal_lahir'])); ?> tahun</span></p>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <i data-lucide="phone" class="h-4 w-4 mr-2 text-purple-500"></i>
                                Kontak
                            </h4>
                            <div class="space-y-2 text-sm">
                                <p><span class="font-medium text-gray-600">Telepon:</span> <span class="text-gray-800"><?php echo e($pasien['no_telepon'] ?? '-'); ?></span></p>
                                <p><span class="font-medium text-gray-600">Alamat:</span> <span class="text-gray-800"><?php echo e($pasien['alamat_lengkap']); ?></span></p>
                            </div>
                        </div>

                        <!-- Medical Info -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <i data-lucide="heart" class="h-4 w-4 mr-2 text-red-500"></i>
                                Info Medis
                            </h4>
                            <div class="space-y-2 text-sm">
                                <p><span class="font-medium text-gray-600">Gol. Darah:</span> <span class="text-gray-800 font-semibold"><?php echo e($pasien['golongan_darah'] ?? '-'); ?></span></p>
                                <p><span class="font-medium text-gray-600">Agama:</span> <span class="text-gray-800"><?php echo e($pasien['agama'] ?? '-'); ?></span></p>
                                <p><span class="font-medium text-gray-600">Gravida Paritas (GPA):</span> <span class="text-gray-800"><?php echo e($pasien['gravida_paritas'] ?? '-'); ?></span></p>
                                <p><span class="font-medium text-gray-600">Riwayat Penyakit:</span> <span class="text-gray-800"><?php echo e($pasien['riwayat_penyakit'] ?? '-'); ?></span></p>
                                <p><span class="font-medium text-gray-600">Hipertensi:</span> <span class="text-gray-800"><?php echo e($pasien['hipertensi'] ?? '-'); ?></span></p>
                                <p><span class="font-medium text-gray-600">Diabetes:</span> <span class="text-gray-800"><?php echo e($pasien['diabetes'] ?? '-'); ?></span></p>
                                <p><span class="font-medium text-gray-600">Alergi Obat:</span> <span class="text-gray-800"><?php echo e($pasien['alergi_obat'] ?? '-'); ?></span></p>
                            </div>
                        </div>

                        <!-- Family Info -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <i data-lucide="users" class="h-4 w-4 mr-2 text-orange-500"></i>
                                Status Keluarga
                            </h4>
                            <div class="space-y-2 text-sm">
                                <p><span class="font-medium text-gray-600">Status:</span> <span class="text-gray-800"><?php echo e($pasien['status_pernikahan'] ?? '-'); ?></span></p>
                                <p><span class="font-medium text-gray-600">Nama Suami:</span> <span class="text-gray-800"><?php echo e($pasien['nama_suami'] ?? '-'); ?></span></p>
                                <p><span class="font-medium text-gray-600">Pekerjaan Suami:</span> <span class="text-gray-800"><?php echo e($pasien['pekerjaan_suami'] ?? '-'); ?></span></p>
                            </div>
                        </div>

                        <!-- Education & Occupation -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <i data-lucide="graduation-cap" class="h-4 w-4 mr-2 text-teal-500"></i>
                                Pendidikan & Pekerjaan
                            </h4>
                            <div class="space-y-2 text-sm">
                                <p><span class="font-medium text-gray-600">Pendidikan:</span> <span class="text-gray-800"><?php echo e($pasien['pendidikan_terakhir'] ?? '-'); ?></span></p>
                                <p><span class="font-medium text-gray-600">Pekerjaan:</span> <span class="text-gray-800"><?php echo e($pasien['pekerjaan'] ?? '-'); ?></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if($pasien['hpht']): ?>
            <!-- Pregnancy Information Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-pink-500 to-rose-600 p-4">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 rounded-full p-2 mr-3">
                            <i data-lucide="clipboard-heart" class="h-6 w-6 text-white"></i>
                        </div>
                        <h6 class="font-bold text-white text-lg">Informasi Kehamilan Saat Ini</h6>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- HPHT Card -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                            <div class="flex items-center mb-2">
                                <i data-lucide="calendar-days" class="h-5 w-5 text-blue-600 mr-2"></i>
                                <h4 class="font-semibold text-blue-800">HPHT</h4>
                            </div>
                            <p class="text-lg font-bold text-blue-900"><?php echo date('d F Y', strtotime($pasien['hpht'])); ?></p>
                            <p class="text-xs text-blue-600 mt-1">Hari Pertama Haid Terakhir</p>
                        </div>

                        <!-- HPL Card -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                            <div class="flex items-center mb-2">
                                <i data-lucide="calendar-check" class="h-5 w-5 text-green-600 mr-2"></i>
                                <h4 class="font-semibold text-green-800">HPL</h4>
                            </div>
                            <p class="text-lg font-bold text-green-900"><?php echo date('d F Y', strtotime(calculateHPL($pasien['hpht']))); ?></p>
                            <p class="text-xs text-green-600 mt-1">Hari Perkiraan Lahir</p>
                        </div>

                        <!-- Gestational Age Card -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                            <div class="flex items-center mb-2">
                                <i data-lucide="clock" class="h-5 w-5 text-purple-600 mr-2"></i>
                                <h4 class="font-semibold text-purple-800">Usia Kehamilan</h4>
                            </div>
                            <p class="text-lg font-bold text-purple-900"><?php echo e(calculateGestationalAgeString($pasien['hpht'])); ?></p>
                            <p class="text-xs text-purple-600 mt-1">Berdasarkan HPHT</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Riwayat ANC -->
        <div x-show="activeTab === 'anc'" class="print-section">
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-red-500 to-pink-600 p-4">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 rounded-full p-2 mr-3">
                            <i data-lucide="heart-pulse" class="h-6 w-6 text-white"></i>
                        </div>
                        <h6 class="font-bold text-white text-lg">Riwayat Kehamilan (ANC)</h6>
                    </div>
                </div>
                <div class="p-6">
                    <?php if (empty($riwayat_anc)): ?>
                        <div class="text-center py-8">
                            <i data-lucide="file-x" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                            <p class="text-gray-500 text-lg">Tidak ada riwayat pemeriksaan ANC</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200">
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Tanggal</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Usia Hamil</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Keluhan</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">TD</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">BB</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">TFU</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">DJJ</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Diagnosis</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php foreach ($riwayat_anc as $anc): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-800"><?php echo date('d/m/Y', strtotime(e($anc['tanggal_kunjungan']))); ?></td>
                                            <td class="px-4 py-3 text-gray-800"><?php echo e($anc['usia_kehamilan']); ?> mg</td>
                                            <td class="px-4 py-3 text-gray-800"><?php echo e($anc['keluhan']); ?></td>
                                            <td class="px-4 py-3 text-gray-800"><?php echo e($anc['tekanan_darah']); ?></td>
                                            <td class="px-4 py-3 text-gray-800"><?php echo e($anc['berat_badan']); ?> kg</td>
                                            <td class="px-4 py-3 text-gray-800"><?php echo e($anc['tfu']); ?> cm</td>
                                            <td class="px-4 py-3 text-gray-800"><?php echo e($anc['djj']); ?> x/mnt</td>
                                            <td class="px-4 py-3 text-gray-800"><?php echo e($anc['diagnosis']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Riwayat Kelahiran Anak -->
        <div x-show="activeTab === 'kelahiran'" class="print-section">
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-blue-500 to-cyan-600 p-4">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 rounded-full p-2 mr-3">
                            <i data-lucide="baby" class="h-6 w-6 text-white"></i>
                        </div>
                        <h6 class="font-bold text-white text-lg">Riwayat Kelahiran Anak</h6>
                    </div>
                </div>
                <div class="p-6">
                    <?php if (empty($riwayat_kelahiran)): ?>
                        <div class="text-center py-8">
                            <i data-lucide="baby" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                            <p class="text-gray-500 text-lg">Tidak ada riwayat kelahiran anak</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200">
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Nama Bayi</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Tanggal Lahir</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">JK</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">BB Lahir</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">PB Lahir</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Lingkar Kepala</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php foreach ($riwayat_kelahiran as $kelahiran): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-800 font-medium"><?php echo e($kelahiran['nama_bayi']); ?></td>
                                            <td class="px-4 py-3 text-gray-800"><?php echo date('d/m/Y', strtotime(e($kelahiran['tanggal_lahir']))); ?><br><span class="text-xs text-gray-500"><?php echo e($kelahiran['jam_lahir']); ?></span></td>
                                            <td class="px-4 py-3 text-gray-800">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($kelahiran['jenis_kelamin']) === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800'; ?>">
                                                    <?php echo e($kelahiran['jenis_kelamin']); ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-gray-800"><?php echo e($kelahiran['berat_lahir']); ?> <span class="text-xs text-gray-500">gr</span></td>
                                            <td class="px-4 py-3 text-gray-800"><?php echo e($kelahiran['panjang_lahir']); ?> <span class="text-xs text-gray-500">cm</span></td>
                                            <td class="px-4 py-3 text-gray-800"><?php echo e($kelahiran['lingkar_kepala']); ?> <span class="text-xs text-gray-500">cm</span></td>
                                            <td class="px-4 py-3 text-gray-800"><?php echo e($kelahiran['catatan_kelahiran']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Riwayat Nifas (PNC) -->
        <div x-show="activeTab === 'nifas'" class="print-section">
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-teal-500 to-emerald-600 p-4">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 rounded-full p-2 mr-3">
                            <i data-lucide="clipboard-check" class="h-6 w-6 text-white"></i>
                        </div>
                        <h6 class="font-bold text-white text-lg">Riwayat Pemeriksaan Nifas (PNC)</h6>
                    </div>
                </div>
                <div class="p-6">
                    <?php if (empty($riwayat_nifas)): ?>
                        <div class="text-center py-8">
                            <i data-lucide="clipboard-check" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                            <p class="text-gray-500 text-lg">Tidak ada riwayat pemeriksaan nifas</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200">
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Tanggal</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Hari Ke-</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">TD</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Suhu</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">TFU</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Lokia</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Keluhan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php foreach ($riwayat_nifas as $nifas): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-800"><?php echo date('d/m/Y', strtotime(e($nifas['tanggal_kunjungan']))); ?></td>
                                            <td class="px-4 py-3 text-gray-800">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                                    Hari <?php echo e($nifas['hari_ke']); ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-gray-800"><?php echo e($nifas['tekanan_darah']); ?></td>
                                            <td class="px-4 py-3 text-gray-800"><?php echo e($nifas['suhu']); ?> <span class="text-xs text-gray-500">°C</span></td>
                                            <td class="px-4 py-3 text-gray-800"><?php echo e($nifas['tfu']); ?> <span class="text-xs text-gray-500">cm</span></td>
                                            <td class="px-4 py-3 text-gray-800"><?php echo e($nifas['lokia']); ?></td>
                                            <td class="px-4 py-3 text-gray-800"><?php echo e($nifas['keluhan']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Riwayat KB -->
        <div x-show="activeTab === 'kb'" class="print-section">
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-yellow-500 to-orange-600 p-4">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 rounded-full p-2 mr-3">
                            <i data-lucide="syringe" class="h-6 w-6 text-white"></i>
                        </div>
                        <h6 class="font-bold text-white text-lg">Riwayat Keluarga Berencana (KB)</h6>
                    </div>
                </div>
                <div class="p-6">
                    <?php if (empty($riwayat_kb)): ?>
                        <div class="text-center py-8">
                            <i data-lucide="syringe" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                            <p class="text-gray-500 text-lg">Tidak ada riwayat pelayanan KB</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200">
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Tanggal</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Jenis Layanan</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Metode KB</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Jadwal Kembali</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php foreach ($riwayat_kb as $kb): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-800"><?php echo date('d/m/Y', strtotime(e($kb['tanggal_layanan']))); ?></td>
                                            <td class="px-4 py-3 text-gray-800">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <?php echo e($kb['jenis_layanan']); ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-gray-800 font-medium"><?php echo e($kb['metode_kb']); ?></td>
                                            <td class="px-4 py-3 text-gray-800">
                                                <?php if ($kb['jadwal_kembali']): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <?php echo date('d/m/Y', strtotime(e($kb['jadwal_kembali']))); ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-400">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Riwayat Imunisasi Anak -->
        <div x-show="activeTab === 'imunisasi'" class="print-section">
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-4">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 rounded-full p-2 mr-3">
                            <i data-lucide="shield-plus" class="h-6 w-6 text-white"></i>
                        </div>
                        <h6 class="font-bold text-white text-lg">Riwayat Imunisasi Anak</h6>
                    </div>
                </div>
                <div class="p-6">
                    <?php if (empty($riwayat_imunisasi_anak)): ?>
                        <div class="text-center py-8">
                            <i data-lucide="shield-plus" class="h-12 w-12 text-gray-400 mx-auto mb-4"></i>
                            <p class="text-gray-500 text-lg">Tidak ada riwayat imunisasi anak</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200">
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Nama Anak</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Tanggal Imunisasi</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Jenis Imunisasi</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php foreach ($riwayat_imunisasi_anak as $imunisasi): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-800 font-medium"><?php echo e($imunisasi['nama_bayi']); ?></td>
                                            <td class="px-4 py-3 text-gray-800"><?php echo date('d/m/Y', strtotime(e($imunisasi['tanggal_imunisasi']))); ?></td>
                                            <td class="px-4 py-3 text-gray-800">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <?php echo e($imunisasi['jenis_imunisasi']); ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-gray-800"><?php echo e($imunisasi['catatan']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alpine.js untuk fungsionalitas Tab -->
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
<!-- CSS Tambahan untuk Cetak -->
<style>
    @media print {
        body {
            font-size: 10pt;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        .no-print { display: none !important; }
        .print-section {
            display: block !important;
            box-shadow: none !important;
            border: 1px solid #e5e7eb !important;
            margin-bottom: 1.5rem;
            page-break-inside: avoid;
        }
        .print-header {
            display: block !important;
        }
        /* Ensure gradients and colors print properly */
        .bg-gradient-to-r {
            background: linear-gradient(to right, #3b82f6, #1d4ed8) !important;
        }
        /* Improve table readability in print */
        table {
            font-size: 9pt !important;
        }
        .inline-flex {
            display: inline-block !important;
        }
        /* Add page margins for better printing */
        @page {
            margin: 0.5in;
        }
    }

    /* Screen styles for better visual hierarchy */
    @media screen {
        .print-section {
            transition: all 0.3s ease;
        }
        .print-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }
    }
</style>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>
