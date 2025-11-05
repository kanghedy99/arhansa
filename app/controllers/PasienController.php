<?php
// File: app/controllers/PasienController.php (Lengkap & Terbaru)
// Controller ini mengatur semua logika untuk modul manajemen pasien.

// Memastikan pengguna sudah login sebelum mengakses halaman manapun di modul ini.
check_login();

// Memuat semua model yang dibutuhkan oleh controller ini.
require_once ROOT_PATH . '/app/models/Pasien.php';
require_once ROOT_PATH . '/app/models/Anc.php';
require_once ROOT_PATH . '/app/models/Kb.php';
require_once ROOT_PATH . '/app/models/Imunisasi.php';
require_once ROOT_PATH . '/app/models/Bayi.php';

// Tambahkan include Validator
require_once ROOT_PATH . '/app/core/Validator.php';
require_once ROOT_PATH . '/app/core/WhatsAppService.php';

// Menentukan aksi yang akan dilakukan berdasarkan parameter dari router.
switch ($action) {
    // Menampilkan halaman utama (daftar semua pasien)
    case 'index':
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10; // Jumlah pasien per halaman
        $pasiens = Pasien::getAll($pdo, $page, $limit);
        $total_pasiens = Pasien::countAll($pdo);
        $total_pages = ceil($total_pasiens / $limit);
        include ROOT_PATH . '/views/pasien/index.php';
        break;

    // Menampilkan form untuk membuat pasien baru
    case 'create':
        // Panggil fungsi untuk membuat No. RM baru
        $next_no_rm = Pasien::generateNewNoRm($pdo);
        include ROOT_PATH . '/views/pasien/create.php';
        break;

    // Menyimpan data pasien baru ke database
    case 'store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf_token($_POST['csrf_token'])) die('Token CSRF tidak valid.');

            // --- IMPLEMENTASI VALIDASI BARU ---
            $validator = new Validator($_POST);
            $validator->rule('required', 'no_rm');
            $validator->rule('required', 'nama_pasien');
            $validator->rule('required', 'tanggal_lahir');
            $validator->rule('date', 'tanggal_lahir');
            $validator->rule('date', 'hpht');
            $validator->rule('numeric', 'nik');

            if ($validator->fails()) {
                // Jika validasi gagal, simpan error dan data lama ke session
                $_SESSION['errors'] = $validator->getErrors();
                $_SESSION['old_input'] = $_POST;
                // Kembali ke halaman form
                header('Location: /pasien/create');
                exit;
            }
            // --- AKHIR IMPLEMENTASI VALIDASI ---

            if (Pasien::isNoRmExists($pdo, $_POST['no_rm'])) {
                $_SESSION['error_message'] = "No. Rekam Medis sudah digunakan.";
                $_SESSION['old_input'] = $_POST;
                header('Location: /pasien/create');
                exit;
            }
            
            Pasien::create($pdo, $_POST);
            $_SESSION['success_message'] = "Data pasien berhasil ditambahkan.";
            header('Location: /pasien');
            exit;
        }
        break;

    // Menampilkan form untuk mengedit data pasien
    case 'edit':
        $pasien = Pasien::findById($pdo, $id);
        if (!$pasien) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }
        include ROOT_PATH . '/views/pasien/edit.php';
        break;

    // Memperbarui data pasien di database
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf_token($_POST['csrf_token'])) die('Token CSRF tidak valid.');
            $id_to_update = $_POST['id'];
            // Validasi: Pastikan No. RM belum ada (kecuali untuk pasien ini sendiri)
            if (Pasien::isNoRmExists($pdo, $_POST['no_rm'], $id_to_update)) {
                $_SESSION['error_message'] = "No. Rekam Medis sudah digunakan oleh pasien lain.";
                header('Location: /pasien/edit/' . $id_to_update);
                exit;
            }
            Pasien::update($pdo, $id_to_update, $_POST);
            $_SESSION['success_message'] = "Data pasien berhasil diperbarui.";
            header('Location: /pasien');
            exit;
        }
        break;

    // Menghapus data pasien (didesain untuk AJAX)
    case 'delete':
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf_token($_POST['csrf_token'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Aksi tidak diizinkan.']);
            exit;
        }
        
        if (Pasien::delete($pdo, $id)) {
            echo json_encode(['success' => true, 'message' => 'Data pasien berhasil dihapus.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus data pasien.']);
        }
        exit;
        break;

    // Menampilkan halaman detail/resume medis pasien
    case 'show':
        $pasien = Pasien::findById($pdo, $id);
        if (!$pasien) {
            http_response_code(404);
            include ROOT_PATH . '/views/errors/404.php';
            exit;
        }

        // Mengumpulkan semua riwayat medis
        $riwayat_anc = Anc::findByPasienId($pdo, $id);
        $riwayat_kb = Kb::findByPasienId($pdo, $id);
        $riwayat_imunisasi_anak = Imunisasi::findByIbuId($pdo, $id);
        $riwayat_kelahiran = Bayi::getAllByPasienId($pdo, $id);
        require_once ROOT_PATH . '/app/models/Nifas.php';
        $riwayat_nifas = Nifas::findByPasienId($pdo, $id);

        // PANGGIL FUNGSI BARU UNTUK STATUS KLINIS
        $status_klinis = Pasien::getClinicalStatus($pdo, $id);
        
        include ROOT_PATH . '/views/pasien/show.php';
        break;

    // CASE BARU: Mengirim notifikasi WhatsApp untuk reminder kunjungan
    case 'send_whatsapp_reminder':
        // Support both GET (for direct redirect) and POST (for AJAX)
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Verify CSRF token from query parameter
            if (!isset($_GET['csrf_token']) || !verify_csrf_token($_GET['csrf_token'])) {
                http_response_code(403);
                echo 'Aksi tidak diizinkan.';
                exit;
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Aksi tidak diizinkan.']);
                exit;
            }
        } else {
            http_response_code(405);
            echo 'Method not allowed.';
            exit;
        }
        
        $pasien = Pasien::findById($pdo, $id);
        if (!$pasien || empty($pasien['no_telepon'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode(['success' => false, 'message' => 'Pasien tidak ditemukan atau nomor telepon kosong.']);
            } else {
                echo 'Pasien tidak ditemukan atau nomor telepon kosong.';
            }
            exit;
        }

        $whatsapp = new WhatsAppService();
        $visit_date = ($_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST['visit_date'] : $_GET['visit_date']) ?? date('Y-m-d', strtotime('+7 days'));

        $result = $whatsapp->sendVisitReminder(
            $pasien['no_telepon'],
            $pasien['nama_pasien'],
            $visit_date
        );

        // Jika berhasil, redirect ke URL WhatsApp
        if ($result['success']) {
            header("Location: " . $result['url']);
            exit;
        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode($result);
            } else {
                echo 'Gagal membuka WhatsApp: ' . $result['message'];
            }
            exit;
        }
        break;

    // CASE BARU: Mengirim notifikasi WhatsApp untuk reminder imunisasi
    case 'send_immunization_reminder':
        // Support both GET (for direct redirect) and POST (for AJAX)
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Verify CSRF token from query parameter
            if (!isset($_GET['csrf_token']) || !verify_csrf_token($_GET['csrf_token'])) {
                http_response_code(403);
                echo 'Aksi tidak diizinkan.';
                exit;
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf_token($_POST['csrf_token'])) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Aksi tidak diizinkan.']);
                exit;
            }
        } else {
            http_response_code(405);
            echo 'Method not allowed.';
            exit;
        }
        
        $pasien = Pasien::findById($pdo, $id);
        if (!$pasien || empty($pasien['no_telepon'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode(['success' => false, 'message' => 'Pasien tidak ditemukan atau nomor telepon kosong.']);
            } else {
                echo 'Pasien tidak ditemukan atau nomor telepon kosong.';
            }
            exit;
        }

        $whatsapp = new WhatsAppService();
        $baby_name = ($_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST['baby_name'] : $_GET['baby_name']) ?? 'Bayi';
        $immunization_type = ($_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST['immunization_type'] : $_GET['immunization_type']) ?? 'Imunisasi';
        $due_date = ($_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST['due_date'] : $_GET['due_date']) ?? date('Y-m-d', strtotime('+7 days'));

        $result = $whatsapp->sendImmunizationReminder(
            $pasien['no_telepon'],
            $pasien['nama_pasien'],
            $baby_name,
            $immunization_type,
            $due_date
        );

        // Jika berhasil, redirect ke URL WhatsApp
        if ($result['success']) {
            header("Location: " . $result['url']);
            exit;
        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                echo json_encode($result);
            } else {
                echo 'Gagal membuka WhatsApp: ' . $result['message'];
            }
            exit;
        }
        break;

    // CASE BARU: Mendapatkan daftar pasien dengan jadwal kunjungan yang akan datang
    case 'upcoming_visits':
        $days_ahead = isset($_GET['days']) ? (int)$_GET['days'] : 7;
        
        $upcoming_kb = Pasien::getPatientsWithUpcomingVisits($pdo, $days_ahead);
        $upcoming_immunizations = Pasien::getPatientsWithUpcomingImmunizations($pdo, $days_ahead);
        $upcoming_anc = Pasien::getPatientsWithUpcomingANC($pdo, $days_ahead);
        
        // Gabungkan semua data
        $all_upcoming = array_merge($upcoming_kb, $upcoming_immunizations, $upcoming_anc);
        
        // Jika request AJAX, return JSON
        if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => [
                    'kb_visits' => $upcoming_kb,
                    'immunizations' => $upcoming_immunizations,
                    'anc_visits' => $upcoming_anc,
                    'all_upcoming' => $all_upcoming
                ]
            ]);
            exit;
        }
        
        // Jika bukan AJAX, tampilkan view (bisa dibuat nanti)
        include ROOT_PATH . '/views/pasien/upcoming_visits.php';
        break;

    // Aksi default jika tidak ada yang cocok
    default:
        http_response_code(404);
        include ROOT_PATH . '/views/errors/404.php';
        break;
}
?>
