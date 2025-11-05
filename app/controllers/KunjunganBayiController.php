<?php
// File: app/controllers/KunjunganBayiController.php - Updated sesuai Standar KN1-KN3 Kemenkes RI

check_login();

require_once ROOT_PATH . '/app/models/KunjunganBayi.php';
require_once ROOT_PATH . '/app/models/Bayi.php';

switch ($action) {
    case 'index':
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $kunjungan_bayi = KunjunganBayi::getAll($pdo, $page, $limit);
        $total_kunjungan = KunjunganBayi::countAll($pdo);
        $total_pages = ceil($total_kunjungan / $limit);
        include ROOT_PATH . '/views/kunjungan_bayi/index.php';
        break;

    case 'create':
        $bayis = Bayi::getAll($pdo, 1, 1000);
        include ROOT_PATH . '/views/kunjungan_bayi/create.php';
        break;

    case 'store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                die('Aksi tidak diizinkan: Token CSRF tidak valid.');
            }

            // Validasi tambahan untuk KN1-KN3
            $errors = [];

            // Validasi tanda bahaya
            if (isset($_POST['tanda_bahaya']) && !empty($_POST['tanda_bahaya']) && $_POST['tanda_bahaya'] != 'Tidak Ada') {
                $_SESSION['warning_message'] = "Perhatian: Ditemukan Tanda Bahaya pada Bayi - Perlu tindakan segera";
            }

            // Validasi ASI eksklusif
            if (isset($_POST['asi_eksklusif']) && $_POST['asi_eksklusif'] == 'Tidak') {
                $_SESSION['warning_message'] = "Perhatian: Bayi tidak mendapat ASI eksklusif - Edukasi pentingnya ASI eksklusif";
            }

            // Validasi status gizi
            if (isset($_POST['status_gizi']) && in_array($_POST['status_gizi'], ['Gizi Buruk', 'Gizi Kurang'])) {
                $_SESSION['warning_message'] = "Perhatian: Status gizi bayi kurang - Perlu intervensi gizi";
            }

            // Validasi Vitamin A
            if (isset($_POST['vitamin_a']) && $_POST['vitamin_a'] == 'Belum') {
                $_SESSION['warning_message'] = "Perhatian: Vitamin A belum diberikan - Pastikan pemberian sesuai jadwal";
            }

            // Validasi skrining tumbuh kembang
            if (isset($_POST['skrining_tumbuh_kembang']) && empty($_POST['skrining_tumbuh_kembang'])) {
                $_SESSION['warning_message'] = "Perhatian: Skrining tumbuh kembang belum dilakukan - Penting untuk deteksi dini gangguan perkembangan";
            }

            // Validasi kunjungan KN berdasarkan usia
            if (isset($_POST['usia_hari'])) {
                $usia_hari = (int)$_POST['usia_hari'];
                if ($usia_hari <= 2) {
                    $expected_kn = 'KN1';
                } elseif ($usia_hari <= 7) {
                    $expected_kn = 'KN2';
                } elseif ($usia_hari <= 28) {
                    $expected_kn = 'KN3';
                } else {
                    $expected_kn = 'Lainnya';
                }

                if (isset($_POST['kunjungan_ke']) && $_POST['kunjungan_ke'] != $expected_kn) {
                    $_SESSION['warning_message'] = "Perhatian: Kunjungan seharusnya $expected_kn berdasarkan usia bayi";
                }
            }

            KunjunganBayi::create($pdo, $_POST);
            $_SESSION['success_message'] = "Data Kunjungan Bayi berhasil disimpan.";
            header('Location: /kunjungan_bayi');
            exit;
        }
        break;

    case 'show':
        if (!$id) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        $kunjungan_data = KunjunganBayi::findById($pdo, $id);
        if (!$kunjungan_data) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        include ROOT_PATH . '/views/kunjungan_bayi/show.php';
        break;

    case 'edit':
        if (!$id) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        $kunjungan_data = KunjunganBayi::findById($pdo, $id);
        if (!$kunjungan_data) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        $bayis = Bayi::getAll($pdo, 1, 1000);
        include ROOT_PATH . '/views/kunjungan_bayi/edit.php';
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                die('Aksi tidak diizinkan: Token CSRF tidak valid.');
            }

            // Validasi sama seperti store
            if (isset($_POST['tanda_bahaya']) && !empty($_POST['tanda_bahaya']) && $_POST['tanda_bahaya'] != 'Tidak Ada') {
                $_SESSION['warning_message'] = "Perhatian: Ditemukan Tanda Bahaya pada Bayi";
            }

            if (isset($_POST['asi_eksklusif']) && $_POST['asi_eksklusif'] == 'Tidak') {
                $_SESSION['warning_message'] = "Perhatian: Bayi tidak mendapat ASI eksklusif";
            }

            KunjunganBayi::update($pdo, $id, $_POST);
            $_SESSION['success_message'] = "Data Kunjungan Bayi berhasil diperbarui.";
            header('Location: /kunjungan_bayi');
            exit;
        }
        break;

    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                die('Aksi tidak diizinkan: Token CSRF tidak valid.');
            }
            KunjunganBayi::delete($pdo, $id);
            $_SESSION['success_message'] = "Data Kunjungan Bayi berhasil dihapus.";
            header('Location: /kunjungan_bayi');
            exit;
        }
        break;

    default:
        http_response_code(404);
        include ROOT_PATH . '/views/errors/404.php';
        break;
}
?>
