<?php
// File: app/controllers/NifasController.php - Updated sesuai Standar KF1-KF4 Kemenkes RI

check_login();

require_once ROOT_PATH . '/app/models/Nifas.php';
require_once ROOT_PATH . '/app/models/Pasien.php';

switch ($action) {
    case 'index':
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $kunjungan_nifas = Nifas::getAll($pdo, $page, $limit);
        $total_nifas = Nifas::countAll($pdo);
        $total_pages = ceil($total_nifas / $limit);
        include ROOT_PATH . '/views/nifas/index.php';
        break;

    case 'create':
        $pasiens = Pasien::getAll($pdo, 1, 1000);
        include ROOT_PATH . '/views/nifas/create.php';
        break;

    case 'get_patient_data':
        if (isset($_GET['pasien_id'])) {
            $pasien = Pasien::findById($pdo, $_GET['pasien_id']);
            if ($pasien) {
                // Hitung hari nifas jika ada HPHT (asumsi HPL = HPHT + 40 minggu)
                $hari_ke = null;
                if ($pasien['hpht']) {
                    $hpl = new DateTime($pasien['hpht']);
                    $hpl->modify('+40 weeks');
                    $today = new DateTime();
                    if ($today >= $hpl) {
                        $hari_ke = $today->diff($hpl)->days + 1; // +1 karena hari pertama nifas
                    }
                }

                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'hari_ke' => $hari_ke,
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

            // Validasi tambahan untuk KF1-KF4
            $errors = [];

            // Validasi tanda bahaya nifas
            if (isset($_POST['tanda_bahaya']) && !empty($_POST['tanda_bahaya']) && $_POST['tanda_bahaya'] != 'Tidak Ada') {
                $_SESSION['warning_message'] = "Perhatian: Ditemukan Tanda Bahaya Nifas - Perlu tindakan segera";
            }

            // Validasi involusi uterus
            if (isset($_POST['involusi_uterus']) && $_POST['involusi_uterus'] == 'Tidak Sesuai') {
                $_SESSION['warning_message'] = "Perhatian: Involusi Uterus Tidak Sesuai (Subinvolusi) - Perlu evaluasi";
            }

            // Validasi ASI
            if (isset($_POST['asi']) && $_POST['asi'] == 'Tidak Lancar') {
                $_SESSION['warning_message'] = "Perhatian: Produksi ASI tidak lancar - Perlu dukungan laktasi";
            }

            // Validasi kunjungan KF
            if (isset($_POST['hari_ke'])) {
                $hari = (int)$_POST['hari_ke'];
                if ($hari <= 3) {
                    $expected_kf = 'KF1';
                } elseif ($hari <= 28) {
                    $expected_kf = 'KF2';
                } elseif ($hari <= 42) {
                    $expected_kf = 'KF3';
                } else {
                    $expected_kf = 'KF4';
                }

                if (isset($_POST['kunjungan_ke']) && $_POST['kunjungan_ke'] != $expected_kf) {
                    $_SESSION['warning_message'] = "Perhatian: Kunjungan seharusnya $expected_kf berdasarkan hari ke-$hari";
                }
            }

            Nifas::create($pdo, $_POST);
            $_SESSION['success_message'] = "Data Kunjungan Nifas berhasil disimpan.";
            header('Location: /nifas');
            exit;
        }
        break;

    case 'show':
        if (!$id) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        $nifas_data = Nifas::findById($pdo, $id);
        if (!$nifas_data) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        include ROOT_PATH . '/views/nifas/show.php';
        break;

    case 'edit':
        if (!$id) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        $nifas_data = Nifas::findById($pdo, $id);
        if (!$nifas_data) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        $pasiens = Pasien::getAll($pdo, 1, 1000);
        include ROOT_PATH . '/views/nifas/edit.php';
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                die('Aksi tidak diizinkan: Token CSRF tidak valid.');
            }

            // Validasi sama seperti store
            if (isset($_POST['tanda_bahaya']) && !empty($_POST['tanda_bahaya']) && $_POST['tanda_bahaya'] != 'Tidak Ada') {
                $_SESSION['warning_message'] = "Perhatian: Ditemukan Tanda Bahaya Nifas";
            }

            if (isset($_POST['involusi_uterus']) && $_POST['involusi_uterus'] == 'Tidak Sesuai') {
                $_SESSION['warning_message'] = "Perhatian: Involusi Uterus Tidak Sesuai (Subinvolusi)";
            }

            Nifas::update($pdo, $id, $_POST);
            $_SESSION['success_message'] = "Data Kunjungan Nifas berhasil diperbarui.";
            header('Location: /nifas');
            exit;
        }
        break;

    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                die('Aksi tidak diizinkan: Token CSRF tidak valid.');
            }
            Nifas::delete($pdo, $id);
            $_SESSION['success_message'] = "Data Kunjungan Nifas berhasil dihapus.";
            header('Location: /nifas');
            exit;
        }
        break;

    default:
        http_response_code(404);
        include ROOT_PATH . '/views/errors/404.php';
        break;
}
?>
