<?php
// File: app/models/Anc.php
// Model untuk mengelola data kunjungan Antenatal Care (ANC).

class Anc {

    // Mengambil semua data kunjungan ANC dengan join ke tabel pasien
    public static function getAll($pdo, $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $stmt = $pdo->prepare("
            SELECT 
                ka.*, p.nama_pasien, p.no_rm 
            FROM 
                kunjungan_anc ka
            JOIN 
                pasiens p ON ka.pasien_id = p.id
            ORDER BY 
                ka.tanggal_kunjungan DESC 
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Menghitung total kunjungan ANC
    public static function countAll($pdo) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM kunjungan_anc");
        return $stmt->fetchColumn();
    }

    // Menyimpan data kunjungan ANC baru
    public static function create($pdo, $data) {
        $sql = "INSERT INTO kunjungan_anc (
                    pasien_id, tanggal_kunjungan, usia_kehamilan, keluhan, berat_badan, 
                    tinggi_badan, tekanan_darah, lila, tfu, djj, presentasi_janin, 
                    hasil_lab, diagnosis, terapi, konseling, jadwal_kunjungan_ulang
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['pasien_id'], $data['tanggal_kunjungan'], $data['usia_kehamilan'], $data['keluhan'], $data['berat_badan'],
            $data['tinggi_badan'], $data['tekanan_darah'], $data['lila'], $data['tfu'], $data['djj'], $data['presentasi_janin'],
            $data['hasil_lab'], $data['diagnosis'], $data['terapi'], $data['konseling'], $data['jadwal_kunjungan_ulang'] ?: null
        ]);
        return $pdo->lastInsertId();
    }
    
    // FUNGSI BARU: Mengambil semua riwayat ANC berdasarkan ID Pasien
    public static function findByPasienId($pdo, $pasien_id) {
        $stmt = $pdo->prepare("SELECT * FROM kunjungan_anc WHERE pasien_id = ? ORDER BY tanggal_kunjungan DESC");
        $stmt->execute([$pasien_id]);
        return $stmt->fetchAll();
    }
    
    // FUNGSI BARU: Menghitung total kunjungan ANC bulan ini
    public static function countThisMonth($pdo) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM kunjungan_anc WHERE MONTH(tanggal_kunjungan) = MONTH(CURRENT_DATE()) AND YEAR(tanggal_kunjungan) = YEAR(CURRENT_DATE())");
        return $stmt->fetchColumn();
    }
}
?>
