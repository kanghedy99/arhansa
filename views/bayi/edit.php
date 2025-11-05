<?php
// Template untuk create.php dan edit.php
$is_edit = isset($bayi);
$form_action = $is_edit ? "/bayi/update" : "/bayi/store";
$page_title = $is_edit ? "Edit Data Bayi" : "Tambah Data Kelahiran Bayi";
$button_text = $is_edit ? "Update Data" : "Simpan Data Kelahiran";

include __DIR__ . '/../layouts/partials/header.php';
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800"><?php echo $page_title; ?></h1>
    <a href="/bayi" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg shadow-sm hover:bg-gray-300">
        <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>Kembali
    </a>
</div>

<div class="bg-white shadow-md rounded-lg max-w-4xl mx-auto">
    <form action="<?php echo $form_action; ?>" method="POST" class="p-6">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        <?php if ($is_edit): ?>
            <input type="hidden" name="id" value="<?php echo e($bayi['id']); ?>">
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label for="nama_bayi" class="block text-sm font-medium text-gray-700">Nama Lengkap Bayi <span class="text-red-500">*</span></label>
                <input type="text" id="nama_bayi" name="nama_bayi" value="<?php echo e($bayi['nama_bayi'] ?? ''); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            
            <div>
                <label for="pasien_id" class="block text-sm font-medium text-gray-700">Ibu <span class="text-red-500">*</span></label>
                <select id="pasien_id" name="pasien_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Pilih Ibu --</option>
                    <?php foreach($pasiens as $pasien): ?>
                        <option value="<?php echo e($pasien['id']); ?>" <?php echo (($bayi['pasien_id'] ?? '') == $pasien['id']) ? 'selected' : ''; ?>>
                            <?php echo e($pasien['nama_pasien']); ?> (RM: <?php echo e($pasien['no_rm']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin <span class="text-red-500">*</span></label>
                <select id="jenis_kelamin" name="jenis_kelamin" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="Laki-laki" <?php echo (($bayi['jenis_kelamin'] ?? '') == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php echo (($bayi['jenis_kelamin'] ?? '') == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                </select>
            </div>

            <div>
                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir <span class="text-red-500">*</span></label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo e($bayi['tanggal_lahir'] ?? date('Y-m-d')); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <div>
                <label for="jam_lahir" class="block text-sm font-medium text-gray-700">Jam Lahir</label>
                <input type="time" id="jam_lahir" name="jam_lahir" value="<?php echo e($bayi['jam_lahir'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <div>
                <label for="berat_lahir" class="block text-sm font-medium text-gray-700">Berat Lahir (gram)</label>
                <input type="number" id="berat_lahir" name="berat_lahir" value="<?php echo e($bayi['berat_lahir'] ?? ''); ?>" placeholder="cth: 3200" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <div>
                <label for="panjang_lahir" class="block text-sm font-medium text-gray-700">Panjang Lahir (cm)</label>
                <input type="number" id="panjang_lahir" name="panjang_lahir" value="<?php echo e($bayi['panjang_lahir'] ?? ''); ?>" placeholder="cth: 50" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

             <div>
                <label for="lingkar_kepala" class="block text-sm font-medium text-gray-700">Lingkar Kepala (cm)</label>
                <input type="number" id="lingkar_kepala" name="lingkar_kepala" value="<?php echo e($bayi['lingkar_kepala'] ?? ''); ?>" placeholder="cth: 34" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <div class="md:col-span-2">
                <label for="catatan_kelahiran" class="block text-sm font-medium text-gray-700">Catatan Kelahiran (APGAR Score, Kondisi, dll)</label>
                <textarea id="catatan_kelahiran" name="catatan_kelahiran" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><?php echo e($bayi['catatan_kelahiran'] ?? ''); ?></textarea>
            </div>
        </div>

        <div class="border-t mt-6 pt-6">
            <button type="submit" class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                <?php echo $button_text; ?>
            </button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>