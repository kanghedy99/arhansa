<?php
// File: app/controllers/KbController.php - Updated sesuai Standar Pelayanan KB Kemenkes RI

check_login();

require_once ROOT_PATH . '/app/models/Kb.php';
require_once ROOT_PATH . '/app/models/Pasien.php';

switch ($action) {
    case 'index':
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $layanan_kb = Kb::getAll($pdo, $page, $limit);
        $total_kb = Kb::countAll($pdo);
        $total_pages = ceil($total_kb / $limit);
        include ROOT_PATH . '/views/kb/index.php';
        break;

    case 'create':
        $pasiens = Pasien::getAll($pdo, 1, 1000);
        include ROOT_PATH . '/views/kb/create.php';
        break;

    case 'store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                die('Aksi tidak diizinkan: Token CSRF tidak valid.');
            }

            // Validasi tambahan untuk KB
            $errors = [];

            // Validasi kontraindikasi
            $kontraindikasi = [];
            if (isset($_POST['riwayat_hipertensi']) && $_POST['riwayat_hipertensi'] == 'Ya') $kontraindikasi[] = 'Hipertensi';
            if (isset($_POST['riwayat_diabetes']) && $_POST['riwayat_diabetes'] == 'Ya') $kontraindikasi[] = 'Diabetes';
            if (isset($_POST['riwayat_jantung']) && $_POST['riwayat_jantung'] == 'Ya') $kontraindikasi[] = 'Penyakit Jantung';
            if (isset($_POST['riwayat_stroke']) && $_POST['riwayat_stroke'] == 'Ya') $kontraindikasi[] = 'Riwayat Stroke';
            if (isset($_POST['riwayat_migrain']) && $_POST['riwayat_migrain'] == 'Ya') $kontraindikasi[] = 'Migrain dengan Aura';
            if (isset($_POST['riwayat_kanker']) && $_POST['riwayat_kanker'] == 'Ya') $kontraindikasi[] = 'Kanker Payudara/Serviks';
            if (isset($_POST['riwayat_liver']) && $_POST['riwayat_liver'] == 'Ya') $kontraindikasi[] = 'Penyakit Liver';
            if (isset($_POST['kebiasaan_merokok']) && $_POST['kebiasaan_merokok'] == 'Ya') $kontraindikasi[] = 'Perokok >35 tahun';

            if (!empty($kontraindikasi)) {
                $_SESSION['warning_message'] = "Perhatian: Ditemukan kontraindikasi KB: " . implode(', ', $kontraindikasi) . " - Perlu konsultasi dokter";
            }

            // Validasi kondisi khusus
            if (isset($_POST['sedang_hamil']) && $_POST['sedang_hamil'] == 'Ya') {
                $_SESSION['warning_message'] = "Perhatian: Pasien sedang hamil - KB tidak boleh diberikan";
            }

            if (isset($_POST['sedang_menyusui']) && $_POST['sedang_menyusui'] == 'Ya' && isset($_POST['masa_nifas']) && $_POST['masa_nifas'] == 'Ya') {
                $_SESSION['warning_message'] = "Perhatian: Pasien dalam masa nifas - Pilih metode KB yang sesuai";
            }

            // Validasi informed consent
            if (isset($_POST['informed_consent']) && $_POST['informed_consent'] != 'Ya') {
                $_SESSION['warning_message'] = "Perhatian: Informed consent belum diberikan - Pastikan pasien memahami risiko dan manfaat KB";
            }

            // Validasi metode KB hormonal untuk perokok
            if (isset($_POST['kebiasaan_merokok']) && $_POST['kebiasaan_merokok'] == 'Ya') {
                $metode_hormonal = ['Pil KB Kombinasi', 'Pil KB Mini (Progestin)', 'Suntik 1 Bulan', 'Suntik 3 Bulan', 'Implan/Susuk'];
                if (isset($_POST['metode_kb']) && in_array($_POST['metode_kb'], $metode_hormonal)) {
                    $_SESSION['warning_message'] = "Perhatian: Perokok >35 tahun tidak dianjurkan menggunakan KB hormonal - Risiko tromboemboli";
                }
            }

            Kb::create($pdo, $_POST);
            $_SESSION['success_message'] = "Data Layanan KB berhasil disimpan.";
            header('Location: /kb');
            exit;
        }
        break;

    case 'show':
        if (!$id) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        $kb_data = Kb::findById($pdo, $id);
        if (!$kb_data) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        include ROOT_PATH . '/views/kb/show.php';
        break;

    case 'edit':
        if (!$id) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        $kb_data = Kb::findById($pdo, $id);
        if (!$kb_data) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        $pasiens = Pasien::getAll($pdo, 1, 1000);
        include ROOT_PATH . '/views/kb/edit.php';
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                die('Aksi tidak diizinkan: Token CSRF tidak valid.');
            }

            // Validasi sama seperti store
            $kontraindikasi = [];
            if (isset($_POST['riwayat_hipertensi']) && $_POST['riwayat_hipertensi'] == 'Ya') $kontraindikasi[] = 'Hipertensi';
            if (isset($_POST['riwayat_diabetes']) && $_POST['riwayat_diabetes'] == 'Ya') $kontraindikasi[] = 'Diabetes';
            if (!empty($kontraindikasi)) {
                $_SESSION['warning_message'] = "Perhatian: Ditemukan kontraindikasi KB: " . implode(', ', $kontraindikasi);
            }

            Kb::update($pdo, $id, $_POST);
            $_SESSION['success_message'] = "Data Layanan KB berhasil diperbarui.";
            header('Location: /kb');
            exit;
        }
        break;

    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                die('Aksi tidak diizinkan: Token CSRF tidak valid.');
            }
            Kb::delete($pdo, $id);
            $_SESSION['success_message'] = "Data Layanan KB berhasil dihapus.";
            header('Location: /kb');
            exit;
        }
        break;

    default:
        http_response_code(404);
        include ROOT_PATH . '/views/errors/404.php';
        break;
}
?>
