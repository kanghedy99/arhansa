<?php
// File: app/models/Kb.php
// Model untuk mengelola data layanan Keluarga Berencana (KB).

class Kb {
    
    // Mengambil semua data layanan KB dengan join ke tabel pasien
    public static function getAll($pdo, $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $stmt = $pdo->prepare("
            SELECT 
                lk.*, p.nama_pasien, p.no_rm 
            FROM 
                layanan_kb lk
            JOIN 
                pasiens p ON lk.pasien_id = p.id
            ORDER BY 
                lk.tanggal_layanan DESC 
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Menghitung total layanan KB
    public static function countAll($pdo) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM layanan_kb");
        return $stmt->fetchColumn();
    }

    // Menyimpan data layanan KB baru
    public static function create($pdo, $data) {
        $sql = "INSERT INTO layanan_kb (
                    pasien_id, tanggal_layanan, metode_kb, jenis_layanan, 
                    keluhan, tindakan, jadwal_kembali
                ) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['pasien_id'], 
            $data['tanggal_layanan'], 
            $data['metode_kb'], 
            $data['jenis_layanan'],
            $data['keluhan'], 
            $data['tindakan'], 
            $data['jadwal_kembali'] ?: null
        ]);
        return $pdo->lastInsertId();
    }
    
    public static function findByPasienId($pdo, $pasien_id) {
        $stmt = $pdo->prepare("SELECT * FROM layanan_kb WHERE pasien_id = ? ORDER BY tanggal_layanan DESC");
        $stmt->execute([$pasien_id]);
        return $stmt->fetchAll();
    }
    
    public static function countThisMonth($pdo) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM layanan_kb WHERE MONTH(tanggal_layanan) = MONTH(CURRENT_DATE()) AND YEAR(tanggal_layanan) = YEAR(CURRENT_DATE())");
        return $stmt->fetchColumn();
    }
}
?>
