<?php
// File: views/pengaturan/create.php atau edit.php
// Logika untuk membedakan create/edit
$is_edit = isset($user); 
$form_action = $is_edit ? "/pengaturan/update" : "/pengaturan/store";
$page_title = $is_edit ? "Edit Pengguna" : "Tambah Pengguna Baru";
$button_text = $is_edit ? "Update Pengguna" : "Simpan Pengguna";

include __DIR__ . '/../layouts/partials/header.php';
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800"><?php echo $page_title; ?></h1>
    <a href="/pengaturan" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg shadow-sm hover:bg-gray-300">
        <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>Kembali
    </a>
</div>

<div class="bg-white shadow-md rounded-lg max-w-2xl mx-auto">
    <form action="<?php echo $form_action; ?>" method="POST" class="p-6">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        <?php if ($is_edit): ?>
            <input type="hidden" name="id" value="<?php echo e($user['id']); ?>">
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="<?php echo e($user['name'] ?? ''); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="<?php echo e($user['email'] ?? ''); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <?php if ($is_edit): ?>
                    <p class="mt-2 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password.</p>
                <?php else: ?>
                    <p class="mt-2 text-xs text-gray-500">Password wajib diisi untuk pengguna baru.</p>
                <?php endif; ?>
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Peran (Role) <span class="text-red-500">*</span></label>
                <select id="role" name="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="bidan" <?php echo (($user['role'] ?? '') == 'bidan') ? 'selected' : ''; ?>>Bidan</option>
                    <option value="admin" <?php echo (($user['role'] ?? '') == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
        </div>

        <div class="border-t mt-6 pt-6">
            <button type="submit" class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                <?php echo $button_text; ?>
            </button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>