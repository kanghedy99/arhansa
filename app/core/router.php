<?php
// File: index.php (atau public/index.php)
// Ini adalah file router utama dan titik masuk tunggal untuk seluruh aplikasi.

// 1. Definisi Konstanta dan Konfigurasi Awal
// --------------------------------------------------------
// Definisikan ROOT_PATH agar kita bisa memanggil file dari mana saja dengan path yang konsisten.
//define('ROOT_PATH', dirname(__FILE__));
define('ROOT_PATH', 'localhost/arhansa/');

// Muat file fungsi bantuan yang akan digunakan di seluruh aplikasi.
require_once ROOT_PATH . '/app/core/functions.php';

// Mulai session dengan aman. Ini harus dipanggil sebelum output apapun.
start_secure_session();

// Muat koneksi database. Variabel $pdo akan tersedia dari sini.
require_once ROOT_PATH . '/app/core/database.php';


// 2. Analisis dan Penguraian URL (Routing)
// --------------------------------------------------------
// Ambil path dari URL, hilangkan query string (misal: ?page=2)
$request_uri = strtok($_SERVER['REQUEST_URI'], '?');

// Hapus garis miring di awal dan akhir untuk konsistensi
$request_uri = trim($request_uri, '/');

// Pecah URI menjadi beberapa bagian berdasarkan '/'
$segments = explode('/', $request_uri);

// Tentukan controller, action, dan ID dari URL
// Contoh: /pasien/edit/15 -> $controller = 'pasien', $action = 'edit', $id = 15
$controller = !empty($segments[0]) ? $segments[0] : 'dashboard'; // Default ke dashboard
$action     = !empty($segments[1]) ? $segments[1] : 'index';     // Default ke index
$id         = !empty($segments[2]) ? $segments[2] : null;        // ID bersifat opsional


// 3. Pemetaan URL ke Controller (Dispatcher)
// --------------------------------------------------------
// Switch statement ini adalah inti dari router.
// Ia memetakan nama controller dari URL ke file controller yang sesuai.

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
}

?>
