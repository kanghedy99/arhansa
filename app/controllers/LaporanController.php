<?php
// File: app/controllers/LaporanController.php - Fixed and Merged Version

check_login();

// Include all required models
require_once ROOT_PATH . '/app/models/Anc.php';
require_once ROOT_PATH . '/app/models/Inc.php';
require_once ROOT_PATH . '/app/models/Nifas.php';
require_once ROOT_PATH . '/app/models/Bayi.php';
require_once ROOT_PATH . '/app/models/Kb.php';
require_once ROOT_PATH . '/app/models/KunjunganBayi.php';
require_once ROOT_PATH . '/app/models/Imunisasi.php';
require_once ROOT_PATH . '/app/models/Laporan.php';
require_once ROOT_PATH . '/app/models/User.php';

// Initialize global user data for sidebar
global $currentUser, $profilePicture;
if (!isset($_SESSION['user_id'])) {
    header('Location: /views/auth/login.php');
    exit();
}
$currentUser = User::findById($pdo, $_SESSION['user_id']);
$profilePicture = $currentUser['profile_picture'] ?? '/assets/images/profile.jpg';

class LaporanController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        include ROOT_PATH . '/views/laporan/index.php';
    }

    // LAPORAN KUNJUNGAN
    public function kunjungan() {
        $tanggal_awal = $_POST['tanggal_awal'] ?? date('Y-m-d');
        $tanggal_akhir = $_POST['tanggal_akhir'] ?? date('Y-m-d');
        $laporan_kunjungan = Laporan::getKunjunganByDateRange($this->pdo, $tanggal_awal, $tanggal_akhir);
        include ROOT_PATH . '/views/laporan/kunjungan.php';
    }

    // LAPORAN KOHORT BAYI
    public function kohort() {
        $tahun = $_POST['tahun'] ?? date('Y');
        $data = Laporan::getLaporanKohort($this->pdo, $tahun);
        $data['tahun'] = $tahun;
        include ROOT_PATH . '/views/laporan/kohort.php';
    }

    /**
     * Helper function baru untuk mengambil data PWS KIA.
     * Ini memisahkan logika pengambilan data dari tampilan.
     */
    private function getPwsKiaData($tahun, $bulan) {
        // CATATAN: Query di bawah ini (misalnya Anc::countThisMonth)
        // tampaknya tidak menggunakan $tahun dan $bulan.
        // Ini perlu diperbaiki di dalam Model (misal: Anc.php)
        // agar laporannya sesuai dengan periode yang dipilih.
        // Untuk saat ini, kita gunakan logika yang ada.

        $data['anc'] = [
            'total_kunjungan' => Anc::countThisMonth($this->pdo),
            'kunjungan_k1' => $this->countAncByKunjunganKe('K1'),
            'kunjungan_k4' => $this->countAncByKunjunganKe('K4'),
            'risiko_tinggi' => Anc::countHighRisk($this->pdo),
            'tablet_fe' => $this->countAncTabletFe(),
            'imunisasi_tt' => $this->countAncImunisasiTT(),
            'protein_urin' => $this->countAncProteinUrin()
        ];

        $data['persalinan'] = Inc::getDeliveryStatistics($this->pdo);
        $data['nifas'] = Nifas::getNifasStatistics($this->pdo);
        $data['bayi'] = Bayi::countNeonatalEssentials($this->pdo);
        $data['bayi']['total_lahir'] = Bayi::countThisMonth($this->pdo);
        $data['bayi']['bblr'] = Bayi::countBBLR($this->pdo);
        $data['bayi']['asfiksia'] = Bayi::countLowAPGAR($this->pdo);
        $data['kb'] = Kb::getKBStatistics($this->pdo);
        $data['imunisasi'] = $this->getImunisasiStatistics();
        $data['kunjungan_bayi'] = KunjunganBayi::getBayiVisitStatistics($this->pdo);
        
        return $data;
    }

    // LAPORAN PWS KIA
    public function pwsKia() {
        $tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');
        $bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : date('m');

        // Menggunakan helper function baru untuk mengambil data
        $data = $this->getPwsKiaData($tahun, $bulan);

        include ROOT_PATH . '/views/laporan/pws_kia.php';
    }

    // LAPORAN BULANAN
    public function bulanan() {
        $tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');
        $bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : date('m');

        $data = [
            'periode' => date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)),
            'anc' => Anc::get10TStatistics($this->pdo),
            'persalinan' => Inc::getDeliveryStatistics($this->pdo),
            'nifas' => Nifas::getNifasStatistics($this->pdo),
            'bayi' => Bayi::countNeonatalEssentials($this->pdo),
            'kb' => Kb::getKBStatistics($this->pdo),
            'imunisasi' => $this->getImunisasiStatistics(),
            'kunjungan_bayi' => KunjunganBayi::getBayiVisitStatistics($this->pdo)
        ];

        include ROOT_PATH . '/views/laporan/bulanan.php';
    }

    // EXPORT PDF
    public function exportPdf() {
        $jenis = isset($_GET['jenis']) ? $_GET['jenis'] : 'kohort_bayi';
        $tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');
        $bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : date('m');

        // Path ke library FPDF (asumsi diletakkan di app/lib/fpdf/)
        $fpdf_path = ROOT_PATH . '/app/lib/fpdf/fpdf.php';

        if (!file_exists($fpdf_path)) {
            header('Content-Type: application/json');
            // Memberi tahu pengguna jika file library-nya tidak ditemukan
            echo json_encode(['status' => 'error', 'message' => 'Library FPDF tidak ditemukan di ' . $fpdf_path . '. Silakan download dan install FPDF sesuai file README_FPDF.txt.']);
            exit();
        }

        // Memuat library FPDF
        require_once $fpdf_path;

        switch ($jenis) {
            case 'pws_kia':
                // 1. Ambil data
                $data = $this->getPwsKiaData($tahun, $bulan);
                $filename = "laporan_pws_kia_{$tahun}_{$bulan}.pdf";

                // 2. Buat objek PDF
                // 'P' = Portrait (Vertikal), 'mm' = unit milimeter, 'A4' = ukuran kertas
                $pdf = new FPDF('P', 'mm', 'A4');
                $pdf->AddPage();
                
                // 3. Judul Laporan
                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Cell(0, 10, 'Laporan PWS KIA', 0, 1, 'C'); // 0 = lebar penuh, 1 = baris baru, 'C' = center
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, "Periode: " . date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)), 0, 1, 'C');
                $pdf->Ln(10); // Spasi 10mm

                // 4. Header Tabel
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->SetFillColor(230, 230, 230); // Warna abu-abu muda
                $pdf->Cell(60, 8, 'Kategori', 1, 0, 'C', true); // 1 = border, 0 = lanjut kanan, 'C' = center, true = fill
                $pdf->Cell(100, 8, 'Indikator', 1, 0, 'C', true);
                $pdf->Cell(30, 8, 'Jumlah', 1, 1, 'C', true); // 1 = baris baru

                // 5. Isi Tabel
                $pdf->SetFont('Arial', '', 10);
                foreach ($data as $kategori => $indikator) {
                    $pdf->SetFont('Arial', 'B', 10);
                    // Tulis nama kategori, bentangkan di 3 kolom
                    $pdf->SetFillColor(245, 245, 245);
                    $pdf->Cell(190, 8, strtoupper($kategori), 1, 1, 'L', true); 
                    
                    $pdf->SetFont('Arial', '', 10);
                    if (is_array($indikator)) {
                        foreach ($indikator as $key => $value) {
                            $pdf->Cell(60, 8, '', 'L', 0); // Kolom kategori kosong (border kiri)
                            $pdf->Cell(100, 8, $key, 1, 0, 'L');
                            $pdf->Cell(30, 8, $value, 1, 1, 'R'); // 'R' = rata kanan
                        }
                    }
                    // $pdf->Ln(2); // Spasi antar kategori (opsional)
                }

                // 6. Output PDF
                // 'D' = Force Download
                $pdf->Output('D', $filename);
                exit();

            default:
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'Export PDF untuk ' . $jenis . ' belum diimplementasikan.']);
                break;
        }
    }

    // EXPORT EXCEL
    public function exportExcel() {
        $jenis = isset($_GET['jenis']) ? $_GET['jenis'] : 'kohort_bayi';
        $tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');
        $bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : date('m');

        switch ($jenis) {
            case 'pws_kia':
                // 1. Ambil data
                $data = $this->getPwsKiaData($tahun, $bulan);
                $filename = "laporan_pws_kia_{$tahun}_{$bulan}.csv";

                // 2. Set header untuk download file CSV
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="' . $filename . '"');

                // 3. Buka output stream
                $output = fopen('php://output', 'w');

                // 4. Tulis judul
                fputcsv($output, ['Laporan PWS KIA']);
                fputcsv($output, ['Periode', "$bulan - $tahun"]);
                fputcsv($output, []); // Baris kosong

                // 5. Tulis data ke CSV
                fputcsv($output, ['Kategori', 'Indikator', 'Jumlah']);

                foreach ($data as $kategori => $indikator) {
                    fputcsv($output, [strtoupper($kategori), '', '']); // Judul Kategori
                    if (is_array($indikator)) {
                        foreach ($indikator as $key => $value) {
                            fputcsv($output, ['', $key, $value]); // Data Indikator
                        }
                    }
                    fputcsv($output, []); // Baris kosong antar kategori
                }
                
                // 6. Tutup output stream
                fclose($output);
                exit(); // Hentikan eksekusi script

            // case 'kohort':
            //     // TODO: Implementasi export kohort
            //     break;

            default:
                // Tampilkan pesan placeholder jika jenis laporan lain
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'message' => 'Export Excel untuk ' . $jenis . ' akan diimplementasikan']);
                break;
        }
    }

    // HELPER METHODS
    private function countAncByKunjunganKe($kunjungan_ke) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM kunjungan_anc WHERE kunjungan_ke = ?");
        $stmt->execute([$kunjungan_ke]);
        return $stmt->fetchColumn();
    }

    private function countAncTabletFe() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM kunjungan_anc WHERE tablet_fe IS NOT NULL AND tablet_fe != ''");
        return $stmt->fetchColumn();
    }

    private function countAncImunisasiTT() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM kunjungan_anc WHERE imunisasi_tt IS NOT NULL AND imunisasi_tt != ''");
        return $stmt->fetchColumn();
    }

    private function countAncProteinUrin() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM kunjungan_anc WHERE protein_urin IS NOT NULL AND protein_urin != ''");
        return $stmt->fetchColumn();
    }

    private function getImunisasiStatistics() {
        $stmt = $this->pdo->query("
            SELECT
                COUNT(*) as total_imunisasi,
                SUM(CASE WHEN jenis_imunisasi LIKE '%HB-0%' THEN 1 ELSE 0 END) as hb0,
                SUM(CASE WHEN jenis_imunisasi LIKE '%BCG%' THEN 1 ELSE 0 END) as bcg,
                SUM(CASE WHEN jenis_imunisasi LIKE '%DPT%' THEN 1 ELSE 0 END) as dpt,
                SUM(CASE WHEN jenis_imunisasi LIKE '%Polio%' THEN 1 ELSE 0 END) as polio,
                SUM(CASE WHEN jenis_imunisasi LIKE '%Campak%' THEN 1 ELSE 0 END) as campak,
                SUM(CASE WHEN kipi IS NOT NULL AND kipi != '' THEN 1 ELSE 0 END) as kipi_count
            FROM imunisasi
        ");
        return $stmt->fetch();
    }
}

// Main controller logic
switch ($action) {
    case 'index':
        $laporan = new LaporanController($pdo);
        $laporan->index();
        break;

    case 'kunjungan':
        $laporan = new LaporanController($pdo);
        $laporan->kunjungan();
        break;

    case 'kohort':
        $laporan = new LaporanController($pdo);
        $laporan->kohort();
        break;

    case 'pws_kia':
        $laporan = new LaporanController($pdo);
        $laporan->pwsKia();
        break;

    case 'bulanan':
        $laporan = new LaporanController($pdo);
        $laporan->bulanan();
        break;

    case 'export_pdf':
        $laporan = new LaporanController($pdo);
        $laporan->exportPdf();
        break;

    case 'export_excel':
        $laporan = new LaporanController($pdo);
        $laporan->exportExcel();
        break;

    default:
        http_response_code(404);
        include ROOT_PATH . '/views/errors/404.php';
        break;
}
?>