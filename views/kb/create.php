<?php 
// File: views/kb/create.php (Versi Tailwind)
include __DIR__ . '/../layouts/partials/header.php'; 
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Formulir Layanan KB</h1>
    <a href="/kb" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg shadow-sm hover:bg-gray-300">
        <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>Kembali
    </a>
</div>

<div class="bg-white shadow-md rounded-lg max-w-4xl mx-auto">
    <form action="/kb/store" method="POST" class="p-6">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label for="pasien_id" class="block text-sm font-medium text-gray-700">Pilih Pasien <span class="text-red-500">*</span></label>
                <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" id="pasien_id" name="pasien_id" required>
                    <option value="">-- Cari No. RM atau Nama Pasien --</option>
                    <?php foreach ($pasiens as $pasien): ?>
                        <option value="<?php echo e($pasien['id']); ?>"><?php echo e($pasien['no_rm']); ?> - <?php echo e($pasien['nama_pasien']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="tanggal_layanan" class="block text-sm font-medium text-gray-700">Tanggal Layanan <span class="text-red-500">*</span></label>
                <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="tanggal_layanan" name="tanggal_layanan" value="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <div>
                <label for="jadwal_kembali" class="block text-sm font-medium text-gray-700">Jadwal Kembali</label>
                <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="jadwal_kembali" name="jadwal_kembali">
            </div>
            
            <div>
                <label for="metode_kb" class="block text-sm font-medium text-gray-700">Metode KB <span class="text-red-500">*</span></label>
                <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="metode_kb" name="metode_kb" required>
                    <option value="">-- Pilih Metode --</option>
                    <option value="Pil">Pil</option>
                    <option value="Suntik 1 Bulan">Suntik 1 Bulan</option>
                    <option value="Suntik 3 Bulan">Suntik 3 Bulan</option>
                    <option value="Implan">Implan</option>
                    <option value="IUD">IUD</option>
                </select>
            </div>

            <div>
                <label for="jenis_layanan" class="block text-sm font-medium text-gray-700">Jenis Layanan <span class="text-red-500">*</span></label>
                <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="jenis_layanan" name="jenis_layanan" required>
                    <option value="Baru">Baru</option>
                    <option value="Kunjungan Ulang">Kunjungan Ulang</option>
                    <option value="Ganti Metode">Ganti Metode</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label for="keluhan" class="block text-sm font-medium text-gray-700">Keluhan</label>
                <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="keluhan" name="keluhan" rows="3"></textarea>
            </div>

            <div class="md:col-span-2">
                <label for="tindakan" class="block text-sm font-medium text-gray-700">Tindakan / Terapi</label>
                <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="tindakan" name="tindakan" rows="3"></textarea>
            </div>
        </div>
        
        <div class="border-t mt-6 pt-6">
            <button type="submit" class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                Simpan Layanan KB
            </button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>