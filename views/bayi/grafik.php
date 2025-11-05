<?php include __DIR__ . '/../layouts/partials/header.php'; ?>

<!-- Header Halaman -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Grafik Pertumbuhan Bayi</h1>
        <p class="text-gray-600 mt-1"><?php echo e($bayi['nama_bayi']); ?> (Ibu: <?php echo e($bayi['nama_ibu']); ?>)</p>
    </div>
    <a href="/bayi" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg shadow-sm hover:bg-gray-300">
        <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>Kembali ke Daftar Bayi
    </a>
</div>

<?php if (empty($growth_data)): ?>
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
        <p class="font-bold">Informasi</p>
        <p>Belum ada data pertumbuhan (berat/panjang/lingkar kepala) yang tercatat untuk bayi ini.</p>
    </div>
<?php else: ?>
    <div class="bg-white shadow-md rounded-lg p-6">
        <!-- Elemen Canvas untuk menampung grafik -->
        <canvas id="growthChart"></canvas>
    </div>
<?php endif; ?>

<!-- Memuat library Chart.js dari CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Hanya jalankan skrip jika elemen canvas ada
    const ctx = document.getElementById('growthChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line', // Jenis grafik adalah garis
            data: {
                // Mengambil label (tanggal) dari PHP
                labels: <?php echo json_encode($chart_labels); ?>,
                datasets: [
                    {
                        label: 'Berat Badan (Kg)',
                        // Mengambil data berat badan dari PHP
                        data: <?php echo json_encode($chart_berat); ?>,
                        borderColor: 'rgb(59, 130, 246)', // Warna biru
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        yAxisID: 'y', // Menggunakan sumbu Y utama
                    },
                    {
                        label: 'Panjang Badan (Cm)',
                        // Mengambil data panjang badan dari PHP
                        data: <?php echo json_encode($chart_panjang); ?>,
                        borderColor: 'rgb(239, 68, 68)', // Warna merah
                        backgroundColor: 'rgba(239, 68, 68, 0.5)',
                        yAxisID: 'y1', // Menggunakan sumbu Y sekunder
                    },
                    {
                        label: 'Lingkar Kepala (Cm)',
                        // Mengambil data lingkar kepala dari PHP
                        data: <?php echo json_encode($chart_lingkar_kepala); ?>,
                        borderColor: 'rgb(22, 163, 74)', // Warna hijau
                        backgroundColor: 'rgba(22, 163, 74, 0.5)',
                        yAxisID: 'y1', // Juga menggunakan sumbu Y sekunder
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    // Sumbu Y utama (untuk Berat Badan)
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Berat Badan (Kg)'
                        }
                    },
                    // Sumbu Y sekunder (untuk Panjang & Lingkar Kepala)
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Panjang & Lingkar Kepala (Cm)'
                        },
                        // Pastikan grid tidak tumpang tindih
                        grid: {
                            drawOnChartArea: false, 
                        },
                    },
                }
            }
        });
    }
});
</script>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>
