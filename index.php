<?php
// File: index.php (atau public/index.php)
// Ini adalah file router utama dan titik masuk tunggal untuk seluruh aplikasi.

// 1. Definisi Konstanta dan Konfigurasi Awal
// --------------------------------------------------------
define('ROOT_PATH', dirname(__FILE__));
require_once ROOT_PATH . '/app/core/functions.php';
start_secure_session();
require_once ROOT_PATH . '/app/core/database.php';

// --- PENYEMPURNAAN BARU ---
// 2. Muat Pengaturan Aplikasi Secara Global
// --------------------------------------------------------
// Memuat model Pengaturan dan mengambil data klinik.
// Variabel $pengaturan sekarang akan tersedia di semua file controller dan view.
require_once ROOT_PATH . '/app/models/Pengaturan.php';
$pengaturan = Pengaturan::get($pdo);


// 3. Analisis dan Penguraian URL (Routing)
// --------------------------------------------------------
$request_uri = strtok($_SERVER['REQUEST_URI'], '?');
$request_uri = trim($request_uri, '/');
$segments = explode('/', $request_uri);
$controller = !empty($segments[0]) ? $segments[0] : 'dashboard';
$action     = !empty($segments[1]) ? $segments[1] : 'index';
$id         = !empty($segments[2]) ? $segments[2] : null;


// 4. Pemetaan URL ke Controller (Dispatcher)
// --------------------------------------------------------
switch ($controller) {
    case 'dashboard':
        require_once ROOT_PATH . '/app/controllers/DashboardController.php';
        break;

    case 'auth':
        require_once ROOT_PATH . '/app/controllers/AuthController.php';
        break;

    case 'pasien':
        require_once ROOT_PATH . '/app/controllers/PasienController.php';
        break;
        
    case 'bayi':
        require_once ROOT_PATH . '/app/controllers/BayiController.php';
        break;

    case 'anc':
        require_once ROOT_PATH . '/app/controllers/AncController.php';
        break;

    case 'inc':
        require_once ROOT_PATH . '/app/controllers/IncController.php';
        break;
        
    case 'nifas':
        require_once ROOT_PATH . '/app/controllers/NifasController.php';
        break;

    case 'kb':
        require_once ROOT_PATH . '/app/controllers/KbController.php';
        break;

    case 'imunisasi':
        require_once ROOT_PATH . '/app/controllers/ImunisasiController.php';
        break;

    case 'laporan':
        require_once ROOT_PATH . '/app/controllers/LaporanController.php';
        break;

    case 'pengaturan': // Manajemen Pengguna
        require_once ROOT_PATH . '/app/controllers/PengaturanController.php';
        break;

    case 'pengaturan-app': // Pengaturan Aplikasi
        require_once ROOT_PATH . '/app/controllers/PengaturanAppController.php';
        break;

    case 'profile': // Profil Pengguna
        require_once ROOT_PATH . '/app/controllers/ProfileController.php';
        break;

    // Jika tidak ada controller yang cocok, tampilkan halaman 404
    default:
        http_response_code(404);
        include ROOT_PATH . '/views/errors/404.php';
        break;
        
    // RUTE BARU
    case 'kunjungan-bayi':
        require_once ROOT_PATH . '/app/controllers/KunjunganBayiController.php';
        break;

}

?>
