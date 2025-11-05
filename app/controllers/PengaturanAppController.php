<?php
// File: app/controllers/PengaturanAppController.php (Baru)
// Controller untuk mengelola halaman Pengaturan Aplikasi.

check_login();

// Fitur ini hanya bisa diakses oleh pengguna dengan peran 'admin'.
if ($_SESSION['user_role'] !== 'admin') {
    $_SESSION['error_message'] = "Anda tidak memiliki hak akses untuk halaman ini.";
    header('Location: /dashboard');
    exit;
}

require_once ROOT_PATH . '/app/models/Pengaturan.php';

switch ($action) {
    // Menampilkan halaman utama pengaturan
    case 'index':
        $pengaturan = Pengaturan::get($pdo);
        include ROOT_PATH . '/views/pengaturan_app/index.php';
        break;

    // Memproses pembaruan data
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                die('Token CSRF tidak valid.');
            }

            // Get current settings to preserve existing logo
            $currentSettings = Pengaturan::get($pdo);
            $_POST['current_logo_klinik'] = $currentSettings['logo_klinik'] ?? '';

            Pengaturan::update($pdo, $_POST);
            $_SESSION['success_message'] = "Pengaturan aplikasi berhasil diperbarui.";
            header('Location: /pengaturan-app');
            exit;
        }
        break;

    default:
        // Jika aksi tidak ditemukan, kembali ke halaman utama pengaturan
        header('Location: /pengaturan-app');
        exit;
}
?>
