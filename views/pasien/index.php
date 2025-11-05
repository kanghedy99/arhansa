<?php include __DIR__ . '/../layouts/partials/header.php'; ?>

<!-- Notifikasi Dinamis (BARU) -->
<div id="notification" class="hidden fixed top-5 right-5 bg-green-500 text-white py-2 px-4 rounded-lg shadow-lg transition-opacity duration-300"></div>

<!-- Header Halaman -->
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <div><h1 class="text-2xl font-bold text-gray-800">Manajemen Data Pasien</h1></div>
    <a href="/pasien/create" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-indigo-700"><i data-lucide="plus-circle" class="mr-2 h-4 w-4"></i>Tambah Pasien Baru</a>
</div>

<!-- Pencarian -->
<div class="mb-4"><input type="text" id="tableSearch" placeholder="Cari berdasarkan nama atau No. RM..." class="block w-full md:w-1/3 p-2 border border-gray-300 rounded-md shadow-sm"></div>

<!-- Tabel Data -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table id="pasienTable" class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">No. RM</th><th class="px-6 py-3">Nama Pasien</th>
                    <th class="px-6 py-3">Usia</th><th class="px-6 py-3">Alamat</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pasiens as $pasien): ?>
                <tr id="pasien-row-<?php echo e($pasien['id']); ?>">
                    <td class="px-6 py-4 font-medium text-gray-900"><?php echo e($pasien['no_rm']); ?></td>
                    <td class="px-6 py-4">
                        <?php echo e($pasien['nama_pasien']); ?>
                        <?php if($pasien['hpht']): ?>
                            <span class="ml-2 text-xs font-semibold bg-pink-100 text-pink-800 px-2 py-1 rounded-full">Hamil</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4"><?php echo e(calculateAge($pasien['tanggal_lahir'])); ?> thn</td>
                    <td class="px-6 py-4 truncate max-w-xs"><?php echo e($pasien['alamat_lengkap']); ?></td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="/pasien/show/<?php echo $pasien['id']; ?>" class="p-2 text-blue-600 bg-blue-100 rounded-full hover:bg-blue-200" title="Lihat Resume"><i data-lucide="eye"></i></a>
                            <a href="/pasien/edit/<?php echo $pasien['id']; ?>" class="p-2 text-yellow-600 bg-yellow-100 rounded-full hover:bg-yellow-200" title="Edit"><i data-lucide="pencil"></i></a>
                            <?php if (!empty($pasien['no_telepon'])): ?>
                            <button type="button" class="whatsapp-btn p-2 text-green-600 bg-green-100 rounded-full hover:bg-green-200" title="Kirim Reminder WhatsApp" data-id="<?php echo e($pasien['id']); ?>" data-name="<?php echo e($pasien['nama_pasien']); ?>" data-phone="<?php echo e($pasien['no_telepon']); ?>" data-token="<?php echo generate_csrf_token(); ?>">
                                <i data-lucide="message-circle"></i>
                            </button>
                            <?php endif; ?>
                            <button type="button" class="delete-btn p-2 text-red-600 bg-red-100 rounded-full hover:bg-red-200" title="Hapus" data-id="<?php echo e($pasien['id']); ?>" data-token="<?php echo generate_csrf_token(); ?>">
                                <i data-lucide="trash-2"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Skrip AJAX dan Pencarian -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi Notifikasi
    const notification = document.getElementById('notification');
    function showNotification(message, isSuccess = true) {
        notification.textContent = message;
        notification.className = `fixed top-5 right-5 py-2 px-4 rounded-lg shadow-lg transition-opacity duration-300 ${isSuccess ? 'bg-green-500 text-white' : 'bg-red-500 text-white'}`;
        notification.classList.remove('hidden');
        setTimeout(() => { notification.classList.add('hidden'); }, 3000);
    }

    // Fungsi WhatsApp Reminder
    document.querySelectorAll('.whatsapp-btn').forEach(button => {
        button.addEventListener('click', function() {
            const patientId = this.dataset.id;
            const patientName = this.dataset.name;
            const patientPhone = this.dataset.phone;
            const token = this.dataset.token;
            
            // Tampilkan modal konfirmasi
            const confirmed = confirm(`Kirim reminder WhatsApp ke ${patientName} (${patientPhone})?`);
            if (!confirmed) return;
            
            // Disable button sementara
            this.disabled = true;
            this.innerHTML = '<i data-lucide="loader-2" class="animate-spin"></i>';
            
            // Kirim request - sekarang akan redirect langsung ke WhatsApp
            window.location.href = `/pasien/send_whatsapp_reminder/${patientId}?csrf_token=${token}&visit_date=${new Date(Date.now() + 7*24*60*60*1000).toISOString().split('T')[0]}`;
        });
    });

    // Event Listener untuk tombol hapus
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const pasienId = this.dataset.id;
            const csrfToken = this.dataset.token;

            if (confirm('Apakah Anda yakin ingin menghapus data pasien ini?')) {
                const formData = new FormData();
                formData.append('csrf_token', csrfToken);

                fetch(`/pasien/delete/${pasienId}`, {
                    method: 'POST',
                    body: new URLSearchParams(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`pasien-row-${pasienId}`).remove();
                        showNotification(data.message, true);
                    } else {
                        showNotification(data.message, false);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan pada sistem.', false);
                });
            }
        });
    });

    // Skrip Pencarian
    document.getElementById('tableSearch').addEventListener('keyup', function() {
        // ... (kode pencarian tidak berubah)
    });
});
</script>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>
