<?php include __DIR__ . '/../layouts/partials/header.php'; ?>

<h1 class="text-2xl font-bold text-gray-800 mb-6">Profil Saya</h1>

<!-- Notifikasi -->
<?php if (isset($_SESSION['success_message'])): ?>
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert"><p><?php echo e($_SESSION['success_message']); ?></p></div>
<?php unset($_SESSION['success_message']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['error_message'])): ?>
<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert"><p><?php echo e($_SESSION['error_message']); ?></p></div>
<?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<div class="bg-white shadow-md rounded-lg max-w-2xl mx-auto">
    <form action="/profile/update" method="POST" enctype="multipart/form-data" class="p-6">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        <input type="hidden" name="user_email_from_db" value="<?php echo e($user['email']); ?>">

        <div class="space-y-6">
            <div><label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label><input type="text" id="name" name="name" value="<?php echo e($user['name']); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
            <div><label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label><input type="email" id="email" name="email" value="<?php echo e($user['email']); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
            <div><label for="role" class="block text-sm font-medium text-gray-700">Peran (Role)</label><input type="text" id="role" name="role" value="<?php echo e(ucfirst($user['role'])); ?>" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100"></div>

            <!-- Profile Picture Upload -->
            <div>
                <label for="profile_picture" class="block text-sm font-medium text-gray-700">Foto Profil</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <?php if (!empty($user['profile_picture'])): ?>
                    <div class="mt-2">
                        <img src="<?php echo e($user['profile_picture']); ?>" alt="Foto Profil" class="h-20 w-20 rounded-full object-cover border-2 border-gray-200">
                        <p class="mt-1 text-xs text-gray-500">Foto profil saat ini</p>
                    </div>
                <?php endif; ?>
                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah.</p>
            </div>

            <hr>
            <div><label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label><input type="password" id="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password.</p></div>
            <div><label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label><input type="password" id="password_confirmation" name="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
        </div>

        <div class="border-t mt-6 pt-6"><button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Update Profil</button></div>
    </form>
</div>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>
