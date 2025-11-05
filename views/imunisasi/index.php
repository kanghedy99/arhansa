<?php 
// File: views/imunisasi/index.php (Versi Tailwind)
include __DIR__ . '/../layouts/partials/header.php'; 
?>

<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Riwayat Imunisasi</h1>
    <a href="/imunisasi/create" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-indigo-700">
        <i data-lucide="plus-circle" class="mr-2 h-4 w-4"></i>
        Catat Imunisasi Baru
    </a>
</div>

<?php if (isset($_SESSION['success_message'])): ?>
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
    <p><?php echo e($_SESSION['success_message']); ?></p>
</div>
<?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Tanggal</th>
                    <th scope="col" class="px-6 py-3">Nama Bayi</th>
                    <th scope="col" class="px-6 py-3">Nama Ibu (No. RM)</th>
                    <th scope="col" class="px-6 py-3">Jenis Imunisasi</th>
                    <th scope="col" class="px-6 py-3">Catatan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($imunisasi_list)): ?>
                     <tr class="bg-white border-b"><td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada riwayat imunisasi.</td></tr>
                <?php else: ?>
                    <?php foreach ($imunisasi_list as $imunisasi): ?>
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo date('d M Y', strtotime(e($imunisasi['tanggal_imunisasi']))); ?></td>
                        <td class="px-6 py-4 font-medium text-gray-900"><?php echo e($imunisasi['nama_bayi']); ?></td>
                        <td class="px-6 py-4"><?php echo e($imunisasi['nama_ibu']); ?> (<?php echo e($imunisasi['no_rm']); ?>)</td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                <?php echo e($imunisasi['jenis_imunisasi']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4"><?php echo e($imunisasi['catatan']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>