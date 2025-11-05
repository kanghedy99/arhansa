<?php include __DIR__ . '/../layouts/partials/header.php'; ?>

<h1 class="text-2xl font-bold text-gray-800 mb-6">Laporan Kunjungan Pasien</h1>

<div class="no-print bg-white shadow-md rounded-lg mb-6">
    <div class="p-4 bg-gray-50 border-b flex items-center">
        <i data-lucide="filter" class="mr-3 text-gray-600"></i>
        <h6 class="font-semibold text-gray-700">Filter Laporan</h6>
    </div>
    <div class="p-6">
        <form action="/laporan/kunjungan" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label for="tanggal_awal" class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="tanggal_awal" name="tanggal_awal" value="<?php echo e($tanggal_awal); ?>">
            </div>
            <div>
                <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="tanggal_akhir" name="tanggal_akhir" value="<?php echo e($tanggal_akhir); ?>">
            </div>
            <div>
                <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

<div id="laporan-area">
    <!-- KEPALA SURAT (KOP) UNTUK CETAK (BARU) -->
    <div class="print-header hidden mb-4">
        <div class="text-center">
            <h2 class="text-xl font-bold"><?php echo e($pengaturan['nama_klinik']); ?></h2>
            <p class="text-sm"><?php echo e($pengaturan['alamat_klinik']); ?></p>
            <p class="text-sm">Telp: <?php echo e($pengaturan['telepon_klinik']); ?> | Email: <?php echo e($pengaturan['email_klinik']); ?></p>
        </div>
        <hr class="my-2 border-t-2 border-gray-800">
        <h3 class="text-center text-lg font-semibold mt-2">LAPORAN KUNJUNGAN PASIEN</h3>
        <p class="text-center text-sm">Periode: <?php echo date('d F Y', strtotime(e($tanggal_awal))); ?> s/d <?php echo date('d F Y', strtotime(e($tanggal_akhir))); ?></p>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="no-print p-4 bg-gray-50 border-b flex justify-between items-center">
            <h6 class="font-semibold text-gray-700">Hasil Laporan</h6>
            <button class="inline-flex items-center px-3 py-1 bg-green-600 text-white font-semibold text-xs rounded-lg shadow-sm hover:bg-green-700" onclick="window.print();"><i data-lucide="printer" class="mr-2 h-4 w-4"></i>Cetak</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                 <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">No. RM</th>
                        <th class="px-6 py-3">Nama Pasien</th>
                        <th class="px-6 py-3">Jenis Layanan</th>
                        <th class="px-6 py-3">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($laporan_kunjungan)): ?>
                        <tr class="bg-white border-b"><td colspan="6" class="px-6 py-4 text-center">Tidak ada data kunjungan.</td></tr>
                    <?php else: ?>
                        <?php foreach ($laporan_kunjungan as $index => $kunjungan): ?>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4"><?php echo $index + 1; ?></td>
                            <td class="px-6 py-4"><?php echo date('d-m-Y', strtotime(e($kunjungan['tanggal']))); ?></td>
                            <td class="px-6 py-4 font-medium text-gray-900"><?php echo e($kunjungan['no_rm']); ?></td>
                            <td class="px-6 py-4"><?php echo e($kunjungan['nama_pasien']); ?></td>
                            <td class="px-6 py-4"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800"><?php echo e($kunjungan['jenis_layanan']); ?></span></td>
                            <td class="px-6 py-4"><?php echo e($kunjungan['keterangan']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>