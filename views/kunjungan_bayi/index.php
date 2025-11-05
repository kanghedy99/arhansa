<?php include __DIR__ . '/../layouts/partials/header.php'; ?>

<!-- Header Halaman -->
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Riwayat Kunjungan Bayi</h1>
    <a href="/kunjungan-bayi/create" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-indigo-700">
        <i data-lucide="plus-circle" class="mr-2 h-4 w-4"></i>Catat Kunjungan Baru
    </a>
</div>

<!-- Notifikasi -->
<?php if (isset($_SESSION['success_message'])): ?>
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert"><p><?php echo e($_SESSION['success_message']); ?></p></div>
<?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<!-- Tabel Data -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Tanggal</th>
                    <th class="px-6 py-3">Nama Bayi</th>
                    <th class="px-6 py-3">BB/PB/LK</th>
                    <th class="px-6 py-3">Imunisasi Diberikan</th>
                    <th class="px-6 py-3">Catatan Klinis</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($kunjungan_bayi_list)): ?>
                    <tr class="bg-white border-b"><td colspan="5" class="px-6 py-4 text-center">Belum ada riwayat kunjungan.</td></tr>
                <?php else: ?>
                    <?php foreach ($kunjungan_bayi_list as $kunjungan): ?>
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4"><?php echo date('d M Y', strtotime(e($kunjungan['tanggal_kunjungan']))); ?></td>
                        <td class="px-6 py-4 font-medium text-gray-900"><?php echo e($kunjungan['nama_bayi']); ?></td>
                        <td class="px-6 py-4"><?php echo e($kunjungan['berat_badan'] ?? '-'); ?> Kg / <?php echo e($kunjungan['panjang_badan'] ?? '-'); ?> Cm / <?php echo e($kunjungan['lingkar_kepala'] ?? '-'); ?> Cm</td>
                        <td class="px-6 py-4"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800"><?php echo e($kunjungan['jenis_imunisasi'] ?: 'Tidak ada'); ?></span></td>
                        <td class="px-6 py-4"><?php echo e($kunjungan['catatan_klinis']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>
