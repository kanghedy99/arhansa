<?php 
// File: views/inc/index.php (Versi Tailwind)
include __DIR__ . '/../layouts/partials/header.php'; 
?>

<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Riwayat Kunjungan INC</h1>
    <a href="/inc/create" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <i data-lucide="plus-circle" class="mr-2 h-4 w-4"></i>
        Catat Kunjungan Baru
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
                    <th scope="col" class="px-6 py-3">No. RM</th>
                    <th scope="col" class="px-6 py-3">Nama Pasien</th>
                    <th scope="col" class="px-6 py-3">Jam Masuk</th>
                    <th scope="col" class="px-6 py-3">Keluhan</th>
                    <th scope="col" class="px-6 py-3">Diagnosis</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($kunjungan_inc)): ?>
                    <tr class="bg-white border-b"><td colspan="8" class="px-6 py-4 text-center text-gray-500">Belum ada riwayat kunjungan INC.</td></tr>
                <?php else: ?>
                    <?php foreach ($kunjungan_inc as $kunjungan): ?>
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo date('d M Y', strtotime(e($kunjungan['tanggal_kunjungan']))); ?></td>
                        <td class="px-6 py-4 font-medium text-gray-900"><?php echo e($kunjungan['no_rm']); ?></td>
                        <td class="px-6 py-4"><?php echo e($kunjungan['nama_pasien']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo e($kunjungan['jam_masuk']); ?></td>
                        <td class="px-6 py-4 truncate max-w-xs" title="<?php echo e($kunjungan['keluhan']); ?>"><?php echo e($kunjungan['keluhan']); ?></td>
                        <td class="px-6 py-4 truncate max-w-xs" title="<?php echo e($kunjungan['diagnosis']); ?>"><?php echo e($kunjungan['diagnosis']); ?></td>
                        <td class="px-6 py-4">
                            <?php if (!empty($kunjungan['jam_keluar'])): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Selesai
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Dalam Perawatan
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="/inc/show/<?php echo e($kunjungan['id']); ?>" class="p-2 text-blue-600 bg-blue-100 rounded-full hover:bg-blue-200" title="Lihat Detail">
                                    <i data-lucide="eye" class="h-4 w-4"></i>
                                </a>
                                <a href="/inc/edit/<?php echo e($kunjungan['id']); ?>" class="p-2 text-yellow-600 bg-yellow-100 rounded-full hover:bg-yellow-200" title="Edit">
                                    <i data-lucide="edit" class="h-4 w-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if ($total_pages > 1): ?>
<div class="flex justify-center mt-6">
    <nav class="flex items-center space-x-2">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" 
               class="px-3 py-2 text-sm font-medium <?php echo $i == $page ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'; ?> border border-gray-300 rounded-md">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </nav>
</div>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>
