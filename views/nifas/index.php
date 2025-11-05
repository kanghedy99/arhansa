<?php include __DIR__ . '/../layouts/partials/header.php'; ?>

<!-- Header Halaman -->
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Riwayat Pemeriksaan Nifas (PNC)</h1>
    <a href="/nifas/create" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-indigo-700">
        <i data-lucide="plus-circle" class="mr-2 h-4 w-4"></i>Catat Kunjungan Nifas
    </a>
</div>

<!-- Notifikasi -->
<?php if (isset($_SESSION['success_message'])): ?>
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
    <p><?php echo e($_SESSION['success_message']); ?></p>
</div>
<?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<!-- Tabel Data -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Tanggal</th>
                    <th class="px-6 py-3">Nama Pasien (Ibu)</th>
                    <th class="px-6 py-3">Hari Ke-</th>
                    <th class="px-6 py-3">Tekanan Darah</th>
                    <th class="px-6 py-3">TFU</th>
                    <th class="px-6 py-3">Lokia</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($kunjungan_nifas)): ?>
                    <tr class="bg-white border-b"><td colspan="7" class="px-6 py-4 text-center">Belum ada riwayat kunjungan nifas.</td></tr>
                <?php else: ?>
                    <?php foreach ($kunjungan_nifas as $kunjungan): ?>
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4"><?php echo date('d M Y', strtotime(e($kunjungan['tanggal_kunjungan']))); ?></td>
                        <td class="px-6 py-4 font-medium text-gray-900"><?php echo e($kunjungan['nama_pasien']); ?></td>
                        <td class="px-6 py-4"><?php echo e($kunjungan['hari_ke']); ?></td>
                        <td class="px-6 py-4"><?php echo e($kunjungan['tekanan_darah']); ?></td>
                        <td class="px-6 py-4"><?php echo e($kunjungan['tfu']); ?></td>
                        <td class="px-6 py-4"><?php echo e($kunjungan['lokia']); ?></td>
                        <td class="px-6 py-4 text-center">
                            <a href="/pasien/show/<?php echo e($kunjungan['pasien_id']); ?>" class="p-2 text-blue-600 bg-blue-100 rounded-full hover:bg-blue-200" title="Lihat Detail Pasien">
                                <i data-lucide="eye" class="h-4 w-4"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>
