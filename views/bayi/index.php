<?php include __DIR__ . '/../layouts/partials/header.php'; ?>

<!-- Notifikasi Dinamis (Untuk AJAX) -->
<div id="notification" class="hidden fixed top-5 right-5 z-50 bg-green-500 text-white py-2 px-4 rounded-lg shadow-lg transition-opacity duration-300"></div>

<!-- Modal Konfirmasi Hapus (Untuk AJAX) -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-40">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i data-lucide="alert-triangle" class="h-6 w-6 text-red-600"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Hapus Data Bayi</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus data ini? Aksi ini tidak dapat dibatalkan.</p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                    Ya, Hapus
                </button>
                <button id="cancelDeleteBtn" class="mt-2 px-4 py-2 bg-gray-200 text-gray-900 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Header Halaman -->
<div class="flex justify-between items-center mb-6"><h1 class="text-2xl font-bold text-gray-800">Manajemen Data Bayi</h1><a href="/bayi/create" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-indigo-700"><i data-lucide="plus-circle" class="mr-2 h-4 w-4"></i>Tambah Data Bayi</a></div>

<!-- Tabel Data Bayi -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Nama Bayi</th>
                    <th class="px-6 py-3">Usia</th>
                    <th class="px-6 py-3">Jenis Kelamin</th>
                    <th class="px-6 py-3">Nama Ibu (No. RM)</th>
                    <th class="px-6 py-3">BB / PB Lahir</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($bayis)): ?>
                    <tr class="bg-white border-b"><td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada data bayi.</td></tr>
                <?php else: ?>
                    <?php foreach ($bayis as $bayi): ?>
                    <tr id="bayi-row-<?php echo e($bayi['id']); ?>" class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900"><?php echo e($bayi['nama_bayi']); ?></td>
                        <td class="px-6 py-4"><?php echo e(calculateBayiAgeString($bayi['tanggal_lahir'])); ?></td>
                        <td class="px-6 py-4"><?php echo e($bayi['jenis_kelamin']); ?></td>
                        <td class="px-6 py-4"><?php echo e($bayi['nama_ibu']); ?> (<?php echo e($bayi['no_rm']); ?>)</td>
                        <td class="px-6 py-4"><?php echo e($bayi['berat_lahir'] ?? '-'); ?> gr / <?php echo e($bayi['panjang_lahir'] ?? '-'); ?> cm</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="/bayi/grafik/<?php echo $bayi['id']; ?>" class="p-2 text-green-600 bg-green-100 rounded-full hover:bg-green-200" title="Lihat Grafik Pertumbuhan"><i data-lucide="line-chart"></i></a>
                                <a href="/bayi/edit/<?php echo $bayi['id']; ?>" class="p-2 text-yellow-600 bg-yellow-100 rounded-full hover:bg-yellow-200" title="Edit"><i data-lucide="pencil"></i></a>
                                <button type="button" class="delete-btn p-2 text-red-600 bg-red-100 rounded-full hover:bg-red-200" title="Hapus" data-id="<?php echo e($bayi['id']); ?>" data-token="<?php echo generate_csrf_token(); ?>">
                                    <i data-lucide="trash-2"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteModal');
    const cancelBtn = document.getElementById('cancelDeleteBtn');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    const notification = document.getElementById('notification');
    let bayiIdToDelete = null;
    let csrfToken = null;

    function showNotification(message, isSuccess = true) {
        notification.textContent = message;
        notification.className = `fixed top-5 right-5 z-50 py-2 px-4 rounded-lg shadow-lg transition-opacity duration-300 ${isSuccess ? 'bg-green-500 text-white' : 'bg-red-500 text-white'}`;
        notification.classList.remove('hidden');
        setTimeout(() => { notification.classList.add('hidden'); }, 3000);
    }

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            bayiIdToDelete = this.dataset.id;
            csrfToken = this.dataset.token;
            modal.classList.remove('hidden');
        });
    });

    cancelBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    confirmBtn.addEventListener('click', () => {
        if (bayiIdToDelete) {
            const formData = new FormData();
            formData.append('csrf_token', csrfToken);

            fetch(`/bayi/delete/${bayiIdToDelete}`, {
                method: 'POST',
                body: new URLSearchParams(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`bayi-row-${bayiIdToDelete}`).remove();
                    showNotification(data.message, true);
                } else {
                    showNotification(data.message, false);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan pada sistem.', false);
            })
            .finally(() => {
                modal.classList.add('hidden');
                bayiIdToDelete = null;
                csrfToken = null;
            });
        }
    });
});
</script>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>
