<?php include __DIR__ . '/../layouts/partials/header.php'; ?>

<!-- Header Halaman -->
<div class="flex justify-between items-center mb-6"><h1 class="text-2xl font-bold text-gray-800">Formulir Kunjungan Bayi</h1><a href="/kunjungan-bayi" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg shadow-sm hover:bg-gray-300"><i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>Kembali</a></div>

<div class="bg-white shadow-md rounded-lg max-w-4xl mx-auto">
    <form action="/kunjungan-bayi/store" method="POST" class="p-6">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Data Kunjungan -->
            <div class="md:col-span-2"><label for="bayi_id" class="block text-sm font-medium text-gray-700">Pilih Bayi <span class="text-red-500">*</span></label><select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="bayi_id" name="bayi_id" required><option value="">-- Pilih Bayi (Nama Ibu) --</option><?php foreach ($bayis as $bayi): ?><option value="<?php echo e($bayi['id']); ?>"><?php echo e($bayi['nama_bayi']); ?> (Ibu: <?php echo e($bayi['nama_ibu']); ?>)</option><?php endforeach; ?></select></div>
            <div><label for="tanggal_kunjungan" class="block text-sm font-medium text-gray-700">Tanggal Kunjungan <span class="text-red-500">*</span></label><input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="tanggal_kunjungan" name="tanggal_kunjungan" value="<?php echo date('Y-m-d'); ?>" required></div>
            <div><label for="pemberi_layanan" class="block text-sm font-medium text-gray-700">Diberikan Oleh</label><input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" id="pemberi_layanan" name="pemberi_layanan" value="<?php echo e($_SESSION['user_name']); ?>" readonly></div>
            
            <!-- Data Pertumbuhan -->
            <div class="md:col-span-2"><hr><h4 class="text-md font-semibold text-gray-800 mt-4">Pemeriksaan Pertumbuhan</h4></div>
            <div><label for="berat_badan" class="block text-sm font-medium text-gray-700">Berat Badan (Kg)</label><input type="text" id="berat_badan" name="berat_badan" placeholder="cth: 8.5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
            <div><label for="panjang_badan" class="block text-sm font-medium text-gray-700">Panjang Badan (Cm)</label><input type="text" id="panjang_badan" name="panjang_badan" placeholder="cth: 70.2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
            <div><label for="lingkar_kepala" class="block text-sm font-medium text-gray-700">Lingkar Kepala (Cm)</label><input type="text" id="lingkar_kepala" name="lingkar_kepala" placeholder="cth: 45.5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
            
            <!-- Imunisasi & Catatan -->
            <div class="md:col-span-2"><hr><h4 class="text-md font-semibold text-gray-800 mt-4">Imunisasi & Catatan</h4></div>
            <div class="md:col-span-2"><label for="jenis_imunisasi" class="block text-sm font-medium text-gray-700">Imunisasi Diberikan (jika ada)</label><select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="jenis_imunisasi" name="jenis_imunisasi"><option value="">-- Tidak ada Imunisasi --</option><option value="Hepatitis B (HB-0)">Hepatitis B (HB-0)</option><option value="BCG">BCG</option><option value="Polio 1">Polio 1</option><option value="DPT-HB-Hib 1">DPT-HB-Hib 1</option><option value="Polio 2">Polio 2</option><option value="DPT-HB-Hib 2">DPT-HB-Hib 2</option><option value="Polio 3">Polio 3</option><option value="DPT-HB-Hib 3">DPT-HB-Hib 3</option><option value="Polio 4">Polio 4</option><option value="Campak">Campak</option></select></div>
            <div class="md:col-span-2"><label for="catatan_klinis" class="block text-sm font-medium text-gray-700">Catatan Klinis (Keluhan, dll)</label><textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="catatan_klinis" name="catatan_klinis" rows="3"></textarea></div>
        </div>
        <div class="border-t mt-6 pt-6"><button type="submit" class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Simpan Kunjungan</button></div>
    </form>
</div>
<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>
