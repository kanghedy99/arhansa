<?php
// File: app/controllers/IncController.php - Updated sesuai Standar APN Kemenkes RI

check_login();

require_once ROOT_PATH . '/app/models/Inc.php';
require_once ROOT_PATH . '/app/models/Pasien.php';

switch ($action) {
    case 'index':
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $kunjungan_inc = Inc::getAll($pdo, $page, $limit);
        $total_inc = Inc::countAll($pdo);
        $total_pages = ceil($total_inc / $limit);
        include ROOT_PATH . '/views/inc/index.php';
        break;

    case 'create':
        $pasiens = Pasien::getAll($pdo, 1, 1000);
        include ROOT_PATH . '/views/inc/create.php';
        break;

    case 'get_patient_data':
        if (isset($_GET['pasien_id'])) {
            $pasien = Pasien::findById($pdo, $_GET['pasien_id']);
            if ($pasien) {
                // Hitung usia kehamilan dalam minggu jika ada HPHT
                $usia_kehamilan = null;
                if ($pasien['hpht']) {
                    $hpht = new DateTime($pasien['hpht']);
                    $today = new DateTime();
                    $usia_kehamilan = floor($hpht->diff($today)->days / 7);
                }

                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'usia_kehamilan' => $usia_kehamilan,
                    'gravida_paritas' => $pasien['gravida_paritas'] ?? '',
                    'riwayat_penyakit' => $pasien['riwayat_penyakit'] ?? '',
                    'hipertensi' => $pasien['hipertensi'] ?? '',
                    'diabetes' => $pasien['diabetes'] ?? '',
                    'alergi_obat' => $pasien['alergi_obat'] ?? '',
                    'nama_pasien' => $pasien['nama_pasien'] ?? '',
                    'no_rm' => $pasien['no_rm'] ?? '',
                    'tanggal_lahir' => $pasien['tanggal_lahir'] ?? '',
                    'alamat_lengkap' => $pasien['alamat_lengkap'] ?? '',
                    'no_telepon' => $pasien['no_telepon'] ?? '',
                    'hpht' => $pasien['hpht'] ?? '',
                    'nama_suami' => $pasien['nama_suami'] ?? '',
                    'golongan_darah' => $pasien['golongan_darah'] ?? ''
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Pasien tidak ditemukan']);
            }
        }
        exit;
        break;

    case 'store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                die('Aksi tidak diizinkan: Token CSRF tidak valid.');
            }

            // Validasi tambahan untuk APN
            $errors = [];

            // Validasi perdarahan post partum
            if (isset($_POST['perdarahan']) && $_POST['perdarahan'] > 500) {
                $_SESSION['warning_message'] = "Perhatian: Perdarahan >500 ml (Perdarahan Post Partum) - Perlu tindakan segera";
            }

            // Validasi APGAR bayi
            if (isset($_POST['kondisi_bayi']) && $_POST['kondisi_bayi'] == 'Asfiksia Berat') {
                $_SESSION['warning_message'] = "Perhatian: Bayi Asfiksia Berat - Perlu resusitasi intensif";
            }

            // Validasi robekan berat
            if (isset($_POST['robekan_perineum']) && in_array($_POST['robekan_perineum'], ['Derajat III', 'Derajat IV'])) {
                $_SESSION['warning_message'] = "Perhatian: Robekan perineum derajat tinggi - Perlu penjahitan spesialis";
            }

            // Validasi IMD
            if (isset($_POST['imd']) && $_POST['imd'] == 'Tidak') {
                $_SESSION['warning_message'] = "Perhatian: IMD tidak dilakukan - Edukasi pentingnya IMD";
            }

            Inc::create($pdo, $_POST);
            $_SESSION['success_message'] = "Data Persalinan berhasil disimpan.";
            header('Location: /inc');
            exit;
        }
        break;

    case 'show':
        if (!$id) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        $inc_data = Inc::findById($pdo, $id);
        if (!$inc_data) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        include ROOT_PATH . '/views/inc/show.php';
        break;

    case 'edit':
        if (!$id) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        $inc_data = Inc::findById($pdo, $id);
        if (!$inc_data) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        $pasiens = Pasien::getAll($pdo, 1, 1000);
        include ROOT_PATH . '/views/inc/edit.php';
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                die('Aksi tidak diizinkan: Token CSRF tidak valid.');
            }

            // Validasi sama seperti store
            if (isset($_POST['perdarahan']) && $_POST['perdarahan'] > 500) {
                $_SESSION['warning_message'] = "Perhatian: Perdarahan >500 ml (Perdarahan Post Partum)";
            }

            if (isset($_POST['kondisi_bayi']) && $_POST['kondisi_bayi'] == 'Asfiksia Berat') {
                $_SESSION['warning_message'] = "Perhatian: Bayi Asfiksia Berat - Perlu resusitasi intensif";
            }

            Inc::update($pdo, $id, $_POST);
            $_SESSION['success_message'] = "Data Persalinan berhasil diperbarui.";
            header('Location: /inc');
            exit;
        }
        break;

    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                die('Aksi tidak diizinkan: Token CSRF tidak valid.');
            }
            Inc::delete($pdo, $id);
            $_SESSION['success_message'] = "Data Persalinan berhasil dihapus.";
            header('Location: /inc');
            exit;
        }
        break;

    default:
        http_response_code(404);
        include ROOT_PATH . '/views/errors/404.php';
        break;
}
?>
