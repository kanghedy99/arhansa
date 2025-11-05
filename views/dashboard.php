<?php
// file: views/dashboard.php (Enhanced)
include __DIR__ . '/layouts/partials/header.php';
if (!function_exists('e')) { function e($string) { return htmlspecialchars($string, ENT_QUOTES, 'UTF-8'); } }
?>

<!-- Pesan Selamat Datang -->
<h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
<p class="text-gray-600 mb-6 mt-1">Selamat datang kembali, <?php echo e($_SESSION['user_name']); ?>!</p>

<!-- START: Grid Kartu Statistik -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4 mb-8">
    <div class="bg-white p-4 rounded-xl shadow-md flex items-center justify-between">
        <div><p class="text-xs font-medium text-gray-500">Total Pasien</p><p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e($total_pasien); ?></p></div>
        <div class="bg-blue-100 p-3 rounded-full"><i data-lucide="users-2" class="text-blue-600 h-5 w-5"></i></div>
    </div>
    <div class="bg-white p-4 rounded-xl shadow-md flex items-center justify-between">
        <div><p class="text-xs font-medium text-gray-500">ANC Bulan Ini</p><p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e($total_anc_bulan_ini); ?></p></div>
        <div class="bg-green-100 p-3 rounded-full"><i data-lucide="heart-pulse" class="text-green-600 h-5 w-5"></i></div>
    </div>
    <div class="bg-white p-4 rounded-xl shadow-md flex items-center justify-between">
        <div><p class="text-xs font-medium text-gray-500">INC Bulan Ini</p><p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e($total_inc_bulan_ini); ?></p></div>
        <div class="bg-purple-100 p-3 rounded-full"><i data-lucide="activity" class="text-purple-600 h-5 w-5"></i></div>
    </div>
    <div class="bg-white p-4 rounded-xl shadow-md flex items-center justify-between">
        <div><p class="text-xs font-medium text-gray-500">Nifas Bulan Ini</p><p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e($total_nifas_bulan_ini); ?></p></div>
        <div class="bg-pink-100 p-3 rounded-full"><i data-lucide="clipboard-check" class="text-pink-600 h-5 w-5"></i></div>
    </div>
    <div class="bg-white p-4 rounded-xl shadow-md flex items-center justify-between">
        <div><p class="text-xs font-medium text-gray-500">KB Bulan Ini</p><p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e($total_kb_bulan_ini); ?></p></div>
        <div class="bg-yellow-100 p-3 rounded-full"><i data-lucide="syringe" class="text-yellow-600 h-5 w-5"></i></div>
    </div>
    <div class="bg-white p-4 rounded-xl shadow-md flex items-center justify-between">
        <div><p class="text-xs font-medium text-gray-500">Kunjungan Bayi</p><p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e($total_kunjungan_bayi_bulan_ini); ?></p></div>
        <div class="bg-indigo-100 p-3 rounded-full"><i data-lucide="baby" class="text-indigo-600 h-5 w-5"></i></div>
    </div>
</div>
<!-- END: Grid Kartu Statistik -->

<!-- START: Timeline Kunjungan & Statistik -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Timeline Kunjungan 7 Hari Terakhir -->
    <div class="bg-white p-6 rounded-xl shadow-md">
        <div class="flex items-center mb-4">
            <i data-lucide="trending-up" class="text-indigo-600 mr-3"></i>
            <h3 class="text-lg font-semibold text-gray-800">Timeline Kunjungan (7 Hari Terakhir)</h3>
        </div>
        <div class="space-y-3">
            <?php if(empty($daily_visit_summary)): ?>
                <p class="text-gray-500 text-center py-4">Tidak ada kunjungan dalam 7 hari terakhir.</p>
            <?php else: ?>
                <?php foreach($daily_visit_summary as $summary): ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-semibold text-gray-800"><?php echo date('d M Y', strtotime($summary['date'])); ?></p>
                        <p class="text-sm text-gray-600"><?php echo e($summary['services']); ?></p>
                    </div>
                    <div class="text-right">
                        <span class="bg-indigo-100 text-indigo-800 text-sm font-medium px-2.5 py-0.5 rounded-full">
                            <?php echo e($summary['total_visits']); ?> kunjungan
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistik Layanan Bulan Ini -->
    <div class="bg-white p-6 rounded-xl shadow-md">
        <div class="flex items-center mb-4">
            <i data-lucide="bar-chart-3" class="text-indigo-600 mr-3"></i>
            <h3 class="text-lg font-semibold text-gray-800">Statistik Layanan Bulan Ini</h3>
        </div>
        <div class="space-y-3">
            <?php if(empty($monthly_service_stats)): ?>
                <p class="text-gray-500 text-center py-4">Tidak ada data layanan bulan ini.</p>
            <?php else: ?>
                <?php 
                $colors = [
                    'ANC' => 'bg-green-500',
                    'INC' => 'bg-purple-500', 
                    'Nifas' => 'bg-pink-500',
                    'KB' => 'bg-yellow-500',
                    'Imunisasi' => 'bg-blue-500',
                    'Kunjungan Bayi' => 'bg-indigo-500'
                ];
                $max_total = max(array_column($monthly_service_stats, 'total'));
                ?>
                <?php foreach($monthly_service_stats as $stat): ?>
                <div class="flex items-center justify-between">
                    <div class="flex items-center flex-1">
                        <div class="w-3 h-3 rounded-full <?php echo $colors[$stat['jenis_layanan']] ?? 'bg-gray-500'; ?> mr-3"></div>
                        <span class="text-sm font-medium text-gray-700"><?php echo e($stat['jenis_layanan']); ?></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-20 bg-gray-200 rounded-full h-2">
                            <div class="<?php echo $colors[$stat['jenis_layanan']] ?? 'bg-gray-500'; ?> h-2 rounded-full" 
                                 style="width: <?php echo ($stat['total'] / $max_total) * 100; ?>%"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-800 w-8 text-right"><?php echo e($stat['total']); ?></span>
                        <?php if($stat['today'] > 0): ?>
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-1.5 py-0.5 rounded-full">
                                +<?php echo e($stat['today']); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- END: Timeline Kunjungan & Statistik -->

<!-- START: Jadwal Mendatang dengan WhatsApp -->
<div class="bg-white p-6 rounded-xl shadow-md">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center">
            <i data-lucide="calendar-clock" class="text-indigo-600 mr-3"></i>
            <h3 class="text-lg font-semibold text-gray-800">Jadwal Kunjungan Mendatang (30 Hari)</h3>
        </div>
        <div class="flex items-center space-x-2">
            <button id="bulk-whatsapp-btn" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-green-700 disabled:opacity-50" title="Kirim Reminder WhatsApp ke Semua Pasien">
                <i data-lucide="message-circle" class="mr-2 h-4 w-4"></i>
                Kirim Reminder Semua
            </button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-4 py-2">Tanggal</th>
                    <th class="px-4 py-2">Nama Pasien</th>
                    <th class="px-4 py-2">Jenis Kegiatan</th>
                    <th class="px-4 py-2">No. Telepon</th>
                    <th class="px-4 py-2">WhatsApp</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($jadwal_mendatang)): ?>
                    <tr><td colspan="5" class="p-4 text-center">Tidak ada jadwal kunjungan dalam 30 hari ke depan.</td></tr>
                <?php else: ?>
                    <?php foreach($jadwal_mendatang as $jadwal): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold <?php echo (strtotime($jadwal['tanggal_jadwal']) == strtotime('today')) ? 'text-red-600' : ''; ?>">
                            <?php echo date('d M Y', strtotime($jadwal['tanggal_jadwal'])); ?>
                            <?php if (strtotime($jadwal['tanggal_jadwal']) == strtotime('today')) echo " (Hari Ini)"; ?>
                        </td>
                        <td class="px-4 py-3">
                            <a href="/pasien/show/<?php echo e($jadwal['pasien_id']); ?>" class="text-indigo-600 hover:underline">
                                <?php echo e($jadwal['nama_pasien']); ?>
                            </a>
                        </td>
                        <td class="px-4 py-3"><?php echo e($jadwal['jenis_kegiatan']); ?></td>
                        <td class="px-4 py-3"><?php echo e($jadwal['no_telepon'] ?? '-'); ?></td>
                        <td class="px-4 py-3">
                            <?php if (!empty($jadwal['no_telepon'])): ?>
                            <button class="whatsapp-reminder-btn p-2 text-green-600 bg-green-100 rounded-full hover:bg-green-200" 
                                    title="Kirim Reminder WhatsApp" 
                                    data-id="<?php echo e($jadwal['pasien_id']); ?>" 
                                    data-name="<?php echo e($jadwal['nama_pasien']); ?>" 
                                    data-phone="<?php echo e($jadwal['no_telepon']); ?>" 
                                    data-date="<?php echo e($jadwal['tanggal_jadwal']); ?>"
                                    data-type="<?php echo e($jadwal['jenis_kegiatan']); ?>"
                                    data-token="<?php echo generate_csrf_token(); ?>">
                                <i data-lucide="message-circle" class="h-4 w-4"></i>
                            </button>
                            <?php else: ?>
                            <span class="text-gray-400">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- END: Jadwal Mendatang dengan WhatsApp -->

<!-- Notifikasi untuk WhatsApp -->
<div id="whatsapp-notification" class="hidden fixed top-5 right-5 bg-green-500 text-white py-2 px-4 rounded-lg shadow-lg transition-opacity duration-300"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi Notifikasi WhatsApp
    const whatsappNotification = document.getElementById('whatsapp-notification');
    function showWhatsAppNotification(message, isSuccess = true) {
        whatsappNotification.textContent = message;
        whatsappNotification.className = `fixed top-5 right-5 py-2 px-4 rounded-lg shadow-lg transition-opacity duration-300 ${isSuccess ? 'bg-green-500 text-white' : 'bg-red-500 text-white'}`;
        whatsappNotification.classList.remove('hidden');
        setTimeout(() => { whatsappNotification.classList.add('hidden'); }, 4000);
    }

    // Event Listener untuk tombol WhatsApp individual
    document.querySelectorAll('.whatsapp-reminder-btn').forEach(button => {
        button.addEventListener('click', function() {
            const patientId = this.dataset.id;
            const patientName = this.dataset.name;
            const patientPhone = this.dataset.phone;
            const visitDate = this.dataset.date;
            const visitType = this.dataset.type;
            const token = this.dataset.token;
            
            // Tampilkan konfirmasi
            const confirmed = confirm(`Kirim reminder WhatsApp ke ${patientName} (${patientPhone}) untuk ${visitType} pada ${visitDate}?`);
            if (!confirmed) return;
            
            // Disable button sementara
            this.disabled = true;
            this.innerHTML = '<i data-lucide="loader-2" class="animate-spin h-4 w-4"></i>';
            
            // Kirim request
            fetch(`/pasien/send_whatsapp_reminder/${patientId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `csrf_token=${token}&visit_date=${visitDate}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showWhatsAppNotification(`✅ Reminder berhasil dikirim ke ${patientName}`, true);
                } else {
                    showWhatsAppNotification(`❌ Gagal mengirim ke ${patientName}: ${data.message}`, false);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showWhatsAppNotification(`❌ Terjadi kesalahan saat mengirim ke ${patientName}`, false);
            })
            .finally(() => {
                // Re-enable button
                this.disabled = false;
                this.innerHTML = '<i data-lucide="message-circle" class="h-4 w-4"></i>';
                // Re-initialize lucide icons
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        });
    });

    // Event Listener untuk tombol kirim semua
    document.getElementById('bulk-whatsapp-btn').addEventListener('click', function() {
        const reminderButtons = document.querySelectorAll('.whatsapp-reminder-btn');
        
        if (reminderButtons.length === 0) {
            showWhatsAppNotification('❌ Tidak ada pasien dengan nomor telepon untuk dikirim reminder', false);
            return;
        }
        
        const confirmed = confirm(`Kirim reminder WhatsApp ke ${reminderButtons.length} pasien dengan jadwal kunjungan?`);
        if (!confirmed) return;
        
        // Disable bulk button
        this.disabled = true;
        this.innerHTML = '<i data-lucide="loader-2" class="animate-spin mr-2 h-4 w-4"></i>Mengirim...';
        
        let successCount = 0;
        let failCount = 0;
        let processedCount = 0;
        
        // Kirim satu per satu dengan delay
        reminderButtons.forEach((button, index) => {
            setTimeout(() => {
                const patientId = button.dataset.id;
                const patientName = button.dataset.name;
                const visitDate = button.dataset.date;
                const token = button.dataset.token;
                
                // Disable individual button
                button.disabled = true;
                button.innerHTML = '<i data-lucide="loader-2" class="animate-spin h-4 w-4"></i>';
                
                fetch(`/pasien/send_whatsapp_reminder/${patientId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `csrf_token=${token}&visit_date=${visitDate}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        successCount++;
                    } else {
                        failCount++;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    failCount++;
                })
                .finally(() => {
                    processedCount++;
                    
                    // Re-enable individual button
                    button.disabled = false;
                    button.innerHTML = '<i data-lucide="message-circle" class="h-4 w-4"></i>';
                    
                    // Jika semua sudah diproses
                    if (processedCount === reminderButtons.length) {
                        // Re-enable bulk button
                        document.getElementById('bulk-whatsapp-btn').disabled = false;
                        document.getElementById('bulk-whatsapp-btn').innerHTML = '<i data-lucide="message-circle" class="mr-2 h-4 w-4"></i>Kirim Reminder Semua';
                        
                        // Tampilkan hasil
                        if (successCount > 0 && failCount === 0) {
                            showWhatsAppNotification(`✅ Semua ${successCount} reminder berhasil dikirim!`, true);
                        } else if (successCount > 0 && failCount > 0) {
                            showWhatsAppNotification(`⚠️ ${successCount} berhasil, ${failCount} gagal dikirim`, false);
                        } else {
                            showWhatsAppNotification(`❌ Semua ${failCount} reminder gagal dikirim`, false);
                        }
                        
                        // Re-initialize lucide icons
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    }
                });
            }, index * 1000); // Delay 1 detik antar pengiriman
        });
    });
});
</script>

<?php include __DIR__ . '/layouts/partials/footer.php'; ?>
