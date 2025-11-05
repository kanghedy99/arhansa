<?php
// File: app/core/functions.php
// Berisi fungsi-fungsi bantuan yang bisa digunakan di seluruh aplikasi.

// Memulai session dengan aman
function start_secure_session() {
    $session_name = 'secure_session_id'; // Nama session kustom
    $secure = false; // Set ke true jika menggunakan HTTPS
    $httponly = true; // Mencegah akses session via JavaScript

    // Atur parameter cookie
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: /views/auth/login.php?error=Could not initiate a safe session (ini_set)");
        exit();
    }
    
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params(
        $cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        $secure,
        $httponly
    );

    session_name($session_name);
    session_start();
    
    // Regenerasi ID session secara berkala untuk mencegah session fixation
    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = time();
    } else if (time() - $_SESSION['created'] > 1800) { // 30 menit
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }
}

// Fungsi untuk memeriksa apakah pengguna sudah login
function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /views/auth/login.php');
        exit();
    }
}

// Fungsi untuk membersihkan output (mencegah XSS)
function e($string) {
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}

// FUNGSI BARU: Menghitung usia berdasarkan tanggal lahir
function calculateAge($birthDate) {
    if (!$birthDate) return null;
    $date = new DateTime($birthDate);
    $now = new DateTime();
    $interval = $now->diff($date);
    return $interval->y;
}

// FUNGSI BARU: Menghitung Hari Perkiraan Lahir (HPL) dari HPHT menggunakan Rumus Naegele
function calculateHPL($hpht) {
    if (!$hpht) return null;
    $date = new DateTime($hpht);
    $date->modify('+7 day');
    $date->modify('-3 month');
    $date->modify('+1 year');
    return $date->format('Y-m-d');
}

// FUNGSI BARU: Menghitung usia kehamilan dalam format "minggu + hari"
function calculateGestationalAgeString($hpht) {
    if (!$hpht) return null;
    $hphtDate = new DateTime($hpht);
    $now = new DateTime();
    $diff = $now->diff($hphtDate);
    $totalDays = $diff->days;
    
    $weeks = floor($totalDays / 7);
    $days = $totalDays % 7;
    
    return "{$weeks} minggu {$days} hari";
}

// Fungsi untuk menghasilkan token CSRF
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Fungsi untuk memverifikasi token CSRF
function verify_csrf_token($token) {
    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        return true;
    }
    return false;
}

/**
 * FUNGSI BARU: Menghitung usia bayi dalam format "x bulan y hari".
 * @param string $birthDate Tanggal lahir bayi dalam format Y-m-d.
 * @return string|null Usia bayi yang sudah diformat atau null jika tanggal lahir tidak valid.
 */
function calculateBayiAgeString($birthDate) {
    if (!$birthDate) return null;
    $birthDate = new DateTime($birthDate);
    $now = new DateTime();
    $interval = $now->diff($birthDate);
    
    $years = $interval->y;
    $months = $interval->m;
    $days = $interval->d;

    $totalMonths = ($years * 12) + $months;

    if ($totalMonths > 0) {
        return "{$totalMonths} bulan {$days} hari";
    } else {
        return "{$days} hari";
    }
}
?>
