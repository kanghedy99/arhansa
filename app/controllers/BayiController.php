<?php
// File: app/controllers/BayiController.php - Updated sesuai Standar Neonatal Esensial Kemenkes RI

check_login();

require_once ROOT_PATH . '/app/models/Bayi.php';
require_once ROOT_PATH . '/app/models/Pasien.php';

switch ($action) {
    case 'index':
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $bayis = Bayi::getAll($pdo, $page, $limit);
        $total_bayi = Bayi::countAll($pdo);
        $total_pages = ceil($total_bayi / $limit);
        include ROOT_PATH . '/views/bayi/index.php';
        break;

    case 'create':
        $pasiens = Pasien::getAll($pdo, 1, 1000);
        include ROOT_PATH . '/views/bayi/create.php';
        break;

    case 'store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                die('Aksi tidak diizinkan: Token CSRF tidak valid.');
            }

            // Validasi tambahan untuk Neonatal Esensial
            $errors = [];

            // Validasi APGAR Score
            if (isset($_POST['apgar_5_menit']) && $_POST['apgar_5_menit'] < 7) {
                $_SESSION['warning_message'] = "Perhatian: APGAR Score <7 - Bayi Perlu Observasi Ketat";
            }

            // Validasi berat lahir (BBLR)
            if (isset($_POST['berat_lahir']) && $_POST['berat_lahir'] < 2500) {
                $_SESSION['warning_message'] = "Perhatian: BBLR (Berat Badan Lahir Rendah) <2500 gram";
            }

            // Validasi IMD
            if (isset($_POST['imd']) && $_POST['imd'] == 'Tidak') {
                $_SESSION['warning_message'] = "Perhatian: IMD tidak dilakukan - Edukasi pentingnya IMD";
            }

            // Validasi Vitamin K1
            if (isset($_POST['vitamin_k1']) && $_POST['vitamin_k1'] == 'Tidak') {
                $_SESSION['warning_message'] = "Perhatian: Vitamin K1 tidak diberikan - Risiko perdarahan";
            }

            // Validasi HB-0
            if (isset($_POST['hb0_imunisasi']) && $_POST['hb0_imunisasi'] == 'Tidak') {
                $_SESSION['warning_message'] = "Perhatian: HB-0 tidak diberikan - Risiko hepatitis B";
            }

            // Validasi kondisi lahir
            if (isset($_POST['kondisi_lahir']) && $_POST['kondisi_lahir'] == 'Asfiksia Berat') {
                $_SESSION['warning_message'] = "Perhatian: Bayi Asfiksia Berat - Perlu tindakan resusitasi intensif";
            }

            // Validasi kelainan kongenital
            if (isset($_POST['kelainan_kongenital']) && !empty($_POST['kelainan_kongenital']) && $_POST['kelainan_kongenital'] != 'Tidak Ada') {
                $_SESSION['warning_message'] = "Perhatian: Ditemukan kelainan kongenital - Perlu rujukan spesialis";
            }

            Bayi::create($pdo, $_POST);
            $_SESSION['success_message'] = "Data Bayi Baru Lahir berhasil disimpan.";
            header('Location: /bayi');
            exit;
        }
        break;

    case 'show':
        if (!$id) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        $bayi_data = Bayi::findById($pdo, $id);
        if (!$bayi_data) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        // Ambil data pertumbuhan bayi
        $growth_data = Bayi::getGrowthDataById($pdo, $id);
        include ROOT_PATH . '/views/bayi/show.php';
        break;

    case 'edit':
        if (!$id) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        $bayi_data = Bayi::findById($pdo, $id);
        if (!$bayi_data) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        $pasiens = Pasien::getAll($pdo, 1, 1000);
        include ROOT_PATH . '/views/bayi/edit.php';
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                die('Aksi tidak diizinkan: Token CSRF tidak valid.');
            }

            // Validasi sama seperti store
            if (isset($_POST['apgar_5_menit']) && $_POST['apgar_5_menit'] < 7) {
                $_SESSION['warning_message'] = "Perhatian: APGAR Score <7 - Bayi Perlu Observasi Ketat";
            }

            if (isset($_POST['berat_lahir']) && $_POST['berat_lahir'] < 2500) {
                $_SESSION['warning_message'] = "Perhatian: BBLR (Berat Badan Lahir Rendah) <2500 gram";
            }

            Bayi::update($pdo, $id, $_POST);
            $_SESSION['success_message'] = "Data Bayi berhasil diperbarui.";
            header('Location: /bayi');
            exit;
        }
        break;

    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                die('Aksi tidak diizinkan: Token CSRF tidak valid.');
            }
            $result = Bayi::delete($pdo, $id);
            if ($result) {
                $_SESSION['success_message'] = "Data Bayi berhasil dihapus.";
            } else {
                $_SESSION['error_message'] = "Data Bayi tidak dapat dihapus karena masih memiliki data imunisasi.";
            }
            header('Location: /bayi');
            exit;
        }
        break;

    default:
        http_response_code(404);
        include ROOT_PATH . '/views/errors/404.php';
        break;
}
?>
