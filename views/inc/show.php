<?php 
// File: views/inc/show.php (Versi Tailwind)
include __DIR__ . '/../layouts/partials/header.php'; 
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Detail Pemeriksaan INC</h1>
    <div class="flex space-x-2">
        <a href="/inc/edit/<?php echo e($inc_data['id']); ?>" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-yellow-700">
            <i data-lucide="edit" class="mr-2 h-4 w-4"></i>Edit
        </a>
        <a href="/inc" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg shadow-sm hover:bg-gray-300">
            <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Patient Info -->
        <div class="bg-white shadow-md rounded-lg">
            <div class="p-6 border-b font-semibold text-gray-700 flex items-center">
                <i data-lucide="user" class="mr-2 h-5 w-5"></i>
                Informasi Pasien
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">No. RM</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900"><?php echo e($inc_data['no_rm']); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nama Pasien</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900"><?php echo e($inc_data['nama_pasien']); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tanggal Kunjungan</label>
                        <p class="mt-1 text-gray-900"><?php echo date('d F Y', strtotime(e($inc_data['tanggal_kunjungan']))); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Jam Masuk</label>
                        <p class="mt-1 text-gray-900"><?php echo e($inc_data['jam_masuk']); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Anamnesis -->
        <div class="bg-white shadow-md rounded-lg">
            <div class="p-6 border-b font-semibold text-gray-700 flex items-center">
                <i data-lucide="clipboard-list" class="mr-2 h-5 w-5"></i>
                Anamnesis & Riwayat
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Keluhan Utama</label>
                    <p class="mt-1 text-gray-900"><?php echo e($inc_data['keluhan']) ?: '-'; ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Riwayat Kehamilan</label>
                    <p class="mt-1 text-gray-900"><?php echo e($inc_data['riwayat_kehamilan']) ?: '-'; ?></p>
                </div>
            </div>
        </div>

        <!-- Physical Examination -->
        <div class="bg-white shadow-md rounded-lg">
            <div class="p-6 border-b font-semibold text-gray-700 flex items-center">
                <i data-lucide="stethoscope" class="mr-2 h-5 w-5"></i>
                Pemeriksaan Fisik
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <label class="block text-xs font-medium text-gray-500">Tekanan Darah</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900"><?php echo e($inc_data['tekanan_darah']) ?: '-'; ?></p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <label class="block text-xs font-medium text-gray-500">Nadi</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900"><?php echo e($inc_data['nadi']) ?: '-'; ?></p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <label class="block text-xs font-medium text-gray-500">Suhu</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900"><?php echo e($inc_data['suhu']) ?: '-'; ?></p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <label class="block text-xs font-medium text-gray-500">Pernapasan</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900"><?php echo e($inc_data['pernapasan']) ?: '-'; ?></p>
                    </div>
                </div>
                
                <h5 class="text-sm font-semibold text-gray-700 mb-4">Pemeriksaan Obstetri</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <label class="block text-xs font-medium text-gray-500">His (Kontraksi)</label>
                        <p class="mt-1 text-gray-900"><?php echo e($inc_data['his']) ?: '-'; ?></p>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <label class="block text-xs font-medium text-gray-500">DJJ</label>
                        <p class="mt-1 text-gray-900"><?php echo e($inc_data['djj']) ?: '-'; ?></p>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <label class="block text-xs font-medium text-gray-500">Pembukaan Serviks</label>
                        <p class="mt-1 text-gray-900"><?php echo e($inc_data['pembukaan_serviks']) ?: '-'; ?></p>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <label class="block text-xs font-medium text-gray-500">Penurunan Kepala</label>
                        <p class="mt-1 text-gray-900"><?php echo e($inc_data['penurunan_kepala']) ?: '-'; ?></p>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-lg md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500">Ketuban</label>
                        <p class="mt-1 text-gray-900"><?php echo e($inc_data['ketuban']) ?: '-'; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assessment & Planning -->
        <div class="bg-white shadow-md rounded-lg">
            <div class="p-6 border-b font-semibold text-gray-700 flex items-center">
                <i data-lucide="clipboard-check" class="mr-2 h-5 w-5"></i>
                Assessment & Planning
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Diagnosis</label>
                    <p class="mt-1 text-gray-900"><?php echo e($inc_data['diagnosis']) ?: '-'; ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Tindakan/Intervensi</label>
                    <p class="mt-1 text-gray-900"><?php echo e($inc_data['tindakan']) ?: '-'; ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Obat yang Diberikan</label>
                    <p class="mt-1 text-gray-900"><?php echo e($inc_data['obat_diberikan']) ?: '-'; ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Catatan Khusus</label>
                    <p class="mt-1 text-gray-900"><?php echo e($inc_data['catatan_khusus']) ?: '-'; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status -->
        <div class="bg-white shadow-md rounded-lg">
            <div class="p-6 border-b font-semibold text-gray-700 flex items-center">
                <i data-lucide="info" class="mr-2 h-5 w-5"></i>
                Status Kunjungan
            </div>
            <div class="p-6">
                <?php if (!empty($inc_data['jam_keluar'])): ?>
                    <div class="flex items-center justify-center p-4 bg-green-100 rounded-lg">
                        <div class="text-center">
                            <i data-lucide="check-circle" class="h-8 w-8 text-green-600 mx-auto mb-2"></i>
                            <p class="text-sm font-medium text-green-800">Selesai</p>
                            <p class="text-xs text-green-600">Jam Keluar: <?php echo e($inc_data['jam_keluar']); ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="flex items-center justify-center p-4 bg-yellow-100 rounded-lg">
                        <div class="text-center">
                            <i data-lucide="clock" class="h-8 w-8 text-yellow-600 mx-auto mb-2"></i>
                            <p class="text-sm font-medium text-yellow-800">Dalam Perawatan</p>
                            <p class="text-xs text-yellow-600">Masih berlangsung</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Birth Results -->
        <?php if (!empty($inc_data['jam_keluar']) || !empty($inc_data['kondisi_ibu']) || !empty($inc_data['kondisi_bayi'])): ?>
        <div class="bg-white shadow-md rounded-lg">
            <div class="p-6 border-b font-semibold text-gray-700 flex items-center">
                <i data-lucide="baby" class="mr-2 h-5 w-5"></i>
                Hasil Persalinan
            </div>
            <div class="p-6 space-y-4">
                <?php if (!empty($inc_data['jam_keluar'])): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Jam Selesai</label>
                    <p class="mt-1 text-gray-900"><?php echo e($inc_data['jam_keluar']); ?></p>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($inc_data['kondisi_ibu'])): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Kondisi Ibu</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        <?php 
                        switch($inc_data['kondisi_ibu']) {
                            case 'Baik': echo 'bg-green-100 text-green-800'; break;
                            case 'Cukup': echo 'bg-blue-100 text-blue-800'; break;
                            case 'Lemah': echo 'bg-yellow-100 text-yellow-800'; break;
                            case 'Kritis': echo 'bg-red-100 text-red-800'; break;
                            default: echo 'bg-gray-100 text-gray-800';
                        }
                        ?>">
                        <?php echo e($inc_data['kondisi_ibu']); ?>
                    </span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($inc_data['kondisi_bayi'])): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Kondisi Bayi</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        <?php 
                        switch($inc_data['kondisi_bayi']) {
                            case 'Sehat': echo 'bg-green-100 text-green-800'; break;
                            case 'Asfiksia Ringan': echo 'bg-yellow-100 text-yellow-800'; break;
                            case 'Asfiksia Sedang': echo 'bg-orange-100 text-orange-800'; break;
                            case 'Asfiksia Berat': case 'Lahir Mati': echo 'bg-red-100 text-red-800'; break;
                            default: echo 'bg-gray-100 text-gray-800';
                        }
                        ?>">
                        <?php echo e($inc_data['kondisi_bayi']); ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Actions -->
        <div class="bg-white shadow-md rounded-lg">
            <div class="p-6 border-b font-semibold text-gray-700 flex items-center">
                <i data-lucide="settings" class="mr-2 h-5 w-5"></i>
                Aksi
            </div>
            <div class="p-6 space-y-3">
                <a href="/inc/edit/<?php echo e($inc_data['id']); ?>" class="w-full inline-flex justify-center items-center px-4 py-2 bg-yellow-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-yellow-700">
                    <i data-lucide="edit" class="mr-2 h-4 w-4"></i>
                    Edit Data
                </a>
                <button onclick="window.print()" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-blue-700">
                    <i data-lucide="printer" class="mr-2 h-4 w-4"></i>
                    Cetak
                </button>
                <form action="/inc/delete/<?php echo e($inc_data['id']); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-red-700">
                        <i data-lucide="trash-2" class="mr-2 h-4 w-4"></i>
                        Hapus Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>
