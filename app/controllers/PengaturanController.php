<?php
// File: app/controllers/PengaturanController.php (Versi Disempurnakan)

check_login();

// --- PENTING: Pemeriksaan Hak Akses ---
if ($_SESSION['user_role'] !== 'admin') {
    $_SESSION['error_message'] = "Anda tidak memiliki hak akses untuk membuka halaman ini.";
    header('Location: /dashboard');
    exit;
}

require_once ROOT_PATH . '/app/models/User.php';

switch ($action) {
    case 'index':
        $users = User::getAll($pdo);
        include ROOT_PATH . '/views/pengaturan/index.php';
        break;

    case 'create':
        include ROOT_PATH . '/views/pengaturan/create.php';
        break;

    case 'store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf_token($_POST['csrf_token'])) die('Token CSRF tidak valid.');
            if (empty($_POST['password'])) {
                 $_SESSION['error_message'] = "Password wajib diisi untuk pengguna baru.";
                header('Location: /pengaturan/create');
                exit;
            }
            if (User::isEmailExists($pdo, $_POST['email'])) {
                $_SESSION['error_message'] = "Email sudah terdaftar.";
                header('Location: /pengaturan/create');
                exit;
            }
            User::create($pdo, $_POST);
            $_SESSION['success_message'] = "Pengguna baru berhasil ditambahkan.";
            header('Location: /pengaturan');
            exit;
        }
        break;
    
    case 'edit':
        $user = User::findById($pdo, $id);
        if (!$user) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        include ROOT_PATH . '/views/pengaturan/edit.php';
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf_token($_POST['csrf_token'])) die('Token CSRF tidak valid.');
            
            $id_to_update = $_POST['id'];
            if (User::isEmailExists($pdo, $_POST['email'], $id_to_update)) {
                $_SESSION['error_message'] = "Email sudah digunakan oleh pengguna lain.";
                header('Location: /pengaturan/edit/' . $id_to_update);
                exit;
            }

            User::update($pdo, $id_to_update, $_POST);
            $_SESSION['success_message'] = "Data pengguna berhasil diperbarui.";
            header('Location: /pengaturan');
            exit;
        }
        break;

    case 'delete':
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf_token($_POST['csrf_token'])) die('Token CSRF tidak valid.');
            
            // Mencegah admin menghapus akunnya sendiri
            if ($id == $_SESSION['user_id']) {
                $_SESSION['error_message'] = "Anda tidak dapat menghapus akun Anda sendiri.";
                header('Location: /pengaturan');
                exit;
            }

            User::delete($pdo, $id);
            $_SESSION['success_message'] = "Data pengguna berhasil dihapus.";
            header('Location: /pengaturan');
            exit;
        }
        break;

    default:
        $users = User::getAll($pdo);
        include ROOT_PATH . '/views/pengaturan/index.php';
        break;
}
?>
