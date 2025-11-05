<?php
// File: app/controllers/ProfileController.php (Baru)

check_login();

require_once ROOT_PATH . '/app/models/User.php';

switch ($action) {
    case 'edit':
        // Ambil data user yang sedang login dari session
        $user = User::findById($pdo, $_SESSION['user_id']);
        include ROOT_PATH . '/views/profile/edit.php';
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf_token($_POST['csrf_token'])) die('Token CSRF tidak valid.');

            $id_to_update = $_SESSION['user_id'];

            // Validasi email jika diubah
            if ($_POST['email'] !== $_SESSION['user_email_from_db']) { // Anda perlu menyimpan email awal di form
                if (User::isEmailExists($pdo, $_POST['email'], $id_to_update)) {
                    $_SESSION['error_message'] = "Email sudah digunakan oleh pengguna lain.";
                    header('Location: /profile/edit');
                    exit;
                }
            }

            // Validasi password
            if (!empty($_POST['password']) && $_POST['password'] !== $_POST['password_confirmation']) {
                $_SESSION['error_message'] = "Konfirmasi password tidak cocok.";
                header('Location: /profile/edit');
                exit;
            }

            // Get current user data to preserve existing profile picture
            $currentUser = User::findById($pdo, $id_to_update);
            $_POST['current_profile_picture'] = $currentUser['profile_picture'] ?? '';

            User::update($pdo, $id_to_update, $_POST);

            // Update nama di session jika berubah
            $_SESSION['user_name'] = $_POST['name'];

            $_SESSION['success_message'] = "Profil Anda berhasil diperbarui.";
            header('Location: /profile/edit');
            exit;
        }
        break;

    default:
        http_response_code(404);
        include ROOT_PATH . '/views/errors/404.php';
        break;
}
?>
