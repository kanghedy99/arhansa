<?php
// File: app/controllers/AuthController.php
// Menangani semua logika terkait login dan logout

// Pastikan file inti sudah dimuat oleh index.php
// Aksi ditentukan oleh $action dari index.php

if ($action === 'login') {
    // Proses form login jika metode adalah POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validasi sederhana
        if (empty($email) || empty($password)) {
            header('Location: /views/auth/login.php?error=Email dan password wajib diisi');
            exit;
        }

        // Cari user berdasarkan email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Verifikasi password
        if ($user && password_verify($password, $user['password'])) {
            // Jika berhasil, buat session
            session_regenerate_id(true); // Mencegah Session Fixation
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            
            header("Location: /dashboard");
            exit();
        } else {
            // Jika gagal, kembali ke halaman login dengan pesan error
            header('Location: /views/auth/login.php?error=Email atau password salah');
            exit;
        }
    }
} elseif ($action === 'logout') {
    // Hancurkan semua session
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header('Location: /views/auth/login.php');
    exit;
}
?>
