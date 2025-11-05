<?php include __DIR__ . '/../layouts/partials/header.php'; ?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Manajemen Pengguna</h1>
    <a href="/pengaturan/create" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-indigo-700">
        <i data-lucide="plus-circle" class="mr-2 h-4 w-4"></i>
        Tambah Pengguna
    </a>
</div>

<?php if (isset($_SESSION['success_message'])): ?>
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
    <p><?php echo e($_SESSION['success_message']); ?></p>
</div>
<?php unset($_SESSION['success_message']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['error_message'])): ?>
<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
    <p><?php echo e($_SESSION['error_message']); ?></p>
</div>
<?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Peran</th>
                    <th scope="col" class="px-6 py-3">Tanggal Terdaftar</th>
                    <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900"><?php echo e($user['name']); ?></td>
                    <td class="px-6 py-4"><?php echo e($user['email']); ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $user['role'] === 'admin' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'; ?>">
                            <?php echo e(ucfirst($user['role'])); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4"><?php echo date('d M Y', strtotime(e($user['created_at']))); ?></td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="/pengaturan/edit/<?php echo $user['id']; ?>" class="p-2 text-yellow-600 bg-yellow-100 rounded-full hover:bg-yellow-200" title="Edit"><i data-lucide="pencil"></i></a>
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                            <form action="/pengaturan/delete/<?php echo $user['id']; ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                <button type="submit" class="p-2 text-red-600 bg-red-100 rounded-full hover:bg-red-200" title="Hapus"><i data-lucide="trash-2"></i></button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>