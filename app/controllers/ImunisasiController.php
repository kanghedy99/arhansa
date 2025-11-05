<?php
// File: app/controllers/ImunisasiController.php
// Mengatur logika untuk modul Imunisasi

check_login();

require_once ROOT_PATH . '/app/models/Imunisasi.php';
require_once ROOT_PATH . '/app/models/Bayi.php';

switch ($action) {
    case 'index':
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $imunisasi_list = Imunisasi::getAll($pdo, $page, $limit);
        $total_imunisasi = Imunisasi::countAll($pdo);
        $total_pages = ceil($total_imunisasi / $limit);
        
        include ROOT_PATH . '/views/imunisasi/index.php';
        break;

    case 'create':
        // Ambil semua data bayi untuk ditampilkan di dropdown
        $bayis = Bayi::getAll($pdo);
        include ROOT_PATH . '/views/imunisasi/create.php';
        break;

    case 'store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                die('Aksi tidak diizinkan: Token CSRF tidak valid.');
            }
            Imunisasi::create($pdo, $_POST);
            $_SESSION['success_message'] = "Data Imunisasi berhasil dicatat.";
            header('Location: /imunisasi');
            exit;
        }
        break;

    default:
        http_response_code(404);
        include ROOT_PATH . '/views/errors/404.php';
        break;
}
?>
