<?php include __DIR__ . '/../layouts/partials/header.php'; ?>

<h1 class="text-2xl font-bold text-gray-800 mb-6">Pengaturan Aplikasi</h1>

<!-- Notifikasi -->
<?php if (isset($_SESSION['success_message'])): ?>
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert"><p><?php echo e($_SESSION['success_message']); ?></p></div>
<?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<div class="bg-white shadow-md rounded-lg max-w-2xl mx-auto">
    <div class="p-6 border-b"><h3 class="text-lg font-semibold text-gray-800">Informasi Klinik</h3></div>
    <form action="/pengaturan-app/update" method="POST" enctype="multipart/form-data" class="p-6">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        <div class="space-y-6">
            <div><label for="nama_klinik" class="block text-sm font-medium text-gray-700">Nama Klinik</label><input type="text" id="nama_klinik" name="nama_klinik" value="<?php echo e($pengaturan['nama_klinik']); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
            <div><label for="alamat_klinik" class="block text-sm font-medium text-gray-700">Alamat Klinik</label><textarea id="alamat_klinik" name="alamat_klinik" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><?php echo e($pengaturan['alamat_klinik']); ?></textarea></div>
            <div><label for="telepon_klinik" class="block text-sm font-medium text-gray-700">No. Telepon</label><input type="text" id="telepon_klinik" name="telepon_klinik" value="<?php echo e($pengaturan['telepon_klinik']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
            <div><label for="email_klinik" class="block text-sm font-medium text-gray-700">Alamat Email</label><input type="email" id="email_klinik" name="email_klinik" value="<?php echo e($pengaturan['email_klinik']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
            <div>
                <label for="logo_klinik" class="block text-sm font-medium text-gray-700">Logo Klinik</label>
                <input type="file" id="logo_klinik" name="logo_klinik" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <?php if (!empty($pengaturan['logo_klinik'])): ?>
                    <img src="<?php echo e($pengaturan['logo_klinik']); ?>" alt="Logo Klinik" class="mt-2 h-16">
                <?php endif; ?>
            </div>

        </div>
        <div class="border-t mt-6 pt-6"><button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Simpan Pengaturan</button></div>
    </form>
</div>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>
