<?php
// File: app/controllers/AncController.php - Updated sesuai Standar Pemeriksaan 10T Kemenkes RI

check_login();

require_once ROOT_PATH . '/app/models/Anc.php';
require_once ROOT_PATH . '/app/models/Pasien.php';

switch ($action) {
    case 'index':
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $kunjungan_anc = Anc::getAll($pdo, $page, $limit);
        $total_anc = Anc::countAll($pdo);
        $total_pages = ceil($total_anc / $limit);
        include ROOT_PATH . '/views/anc/index.php';
        break;

    case 'create':
        // Ambil semua pasien untuk dropdown, pastikan ada data hpht
        $pasiens = Pasien::getAll($pdo, 1, 1000);
        include ROOT_PATH . '/views/anc/create.php';
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

            // Validasi tambahan untuk Pemeriksaan 10T
            $errors = [];

            // Validasi LILA (KEK jika <23.5 cm)
            if (isset($_POST['lila']) && $_POST['lila'] < 23.5) {
                $_SESSION['warning_message'] = "Perhatian: LILA <23.5 cm (KEK - Kurang Energi Kronis)";
            }

            // Validasi Hb (Anemia jika <11 g/dL)
            if (isset($_POST['hb_hemoglobin']) && $_POST['hb_hemoglobin'] < 11) {
                $_SESSION['warning_message'] = "Perhatian: Hb <11 g/dL (Anemia)";
            }

            // Validasi Kategori Risiko
            if (isset($_POST['kategori_risiko']) && in_array($_POST['kategori_risiko'], ['Tinggi', 'Sangat Tinggi'])) {
                $_SESSION['warning_message'] = "Perhatian: Kehamilan Risiko " . $_POST['kategori_risiko'] . " - Perlu Rujukan";
            }

            // Validasi usia kehamilan
            if (isset($_POST['usia_kehamilan']) && $_POST['usia_kehamilan'] > 42) {
                $_SESSION['warning_message'] = "Perhatian: Usia kehamilan >42 minggu - Perlu evaluasi";
            }

            // Validasi tekanan darah (Preeklampsia jika >140/90)
            if (isset($_POST['tekanan_darah'])) {
                $td = explode('/', $_POST['tekanan_darah']);
                if (count($td) == 2) {
                    $sistolik = (int)$td[0];
                    $diastolik = (int)$td[1];
                    if ($sistolik >= 140 || $diastolik >= 90) {
                        $_SESSION['warning_message'] = "Perhatian: Tekanan darah tinggi - Risiko Preeklampsia";
                    }
                }
            }

            // Validasi protein urin (Preeklampsia jika positif)
            if (isset($_POST['protein_urin']) && in_array($_POST['protein_urin'], ['+1', '+2', '+3', '+4'])) {
                $_SESSION['warning_message'] = "Perhatian: Protein urin positif - Risiko Preeklampsia";
            }

            Anc::create($pdo, $_POST);
            $_SESSION['success_message'] = "Data Kunjungan ANC berhasil disimpan.";
            header('Location: /anc');
            exit;
        }
        break;

    case 'show':
        if (!$id) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        $anc_data = Anc::findById($pdo, $id);
        if (!$anc_data) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        include ROOT_PATH . '/views/anc/show.php';
        break;

    case 'edit':
        if (!$id) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        $anc_data = Anc::findById($pdo, $id);
        if (!$anc_data) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        $pasiens = Pasien::getAll($pdo, 1, 1000);
        include ROOT_PATH . '/views/anc/edit.php';
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                die('Aksi tidak diizinkan: Token CSRF tidak valid.');
            }

            // Validasi sama seperti store
            if (isset($_POST['lila']) && $_POST['lila'] < 23.5) {
                $_SESSION['warning_message'] = "Perhatian: LILA <23.5 cm (KEK - Kurang Energi Kronis)";
            }

            if (isset($_POST['hb_hemoglobin']) && $_POST['hb_hemoglobin'] < 11) {
                $_SESSION['warning_message'] = "Perhatian: Hb <11 g/dL (Anemia)";
            }

            if (isset($_POST['kategori_risiko']) && in_array($_POST['kategori_risiko'], ['Tinggi', 'Sangat Tinggi'])) {
                $_SESSION['warning_message'] = "Perhatian: Kehamilan Risiko " . $_POST['kategori_risiko'] . " - Perlu Rujukan";
            }

            Anc::update($pdo, $id, $_POST);
            $_SESSION['success_message'] = "Data Kunjungan ANC berhasil diperbarui.";
            header('Location: /anc');
            exit;
        }
        break;

    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                die('Aksi tidak diizinkan: Token CSRF tidak valid.');
            }
            Anc::delete($pdo, $id);
            $_SESSION['success_message'] = "Data Kunjungan ANC berhasil dihapus.";
            header('Location: /anc');
            exit;
        }
        break;

    default:
        http_response_code(404);
        include ROOT_PATH . '/views/errors/404.php';
        break;
}
?>
