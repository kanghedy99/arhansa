<?php
// File: app/models/Nifas.php
// Model untuk mengelola data kunjungan Post-Natal Care (PNC) - Updated sesuai Standar KF1-KF4 Kemenkes RI

class Nifas {

    // Mengambil semua data kunjungan nifas dengan join ke tabel pasien
    public static function getAll($pdo, $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $stmt = $pdo->prepare("
            SELECT
                kn.*,
                p.nama_pasien,
                p.no_rm,
                p.gravida_paritas
            FROM
                kunjungan_nifas kn
            JOIN
                pasiens p ON kn.pasien_id = p.id
            ORDER BY
                kn.tanggal_kunjungan DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Menyimpan data kunjungan nifas baru dengan kolom tambahan (KF1-KF4)
    public static function create($pdo, $data) {
        $sql = "INSERT INTO kunjungan_nifas (
                    pasien_id, tanggal_kunjungan, hari_ke, kunjungan_ke,
                    tekanan_darah, suhu, nadi, pernapasan,
                    tfu, lokia, kondisi_perineum,
                    kontraksi_uterus, asi, laktasi, masalah_laktasi,
                    involusi_uterus, vitamin_a, tablet_fe,
                    konseling_kb, pemeriksaan_payudara, tanda_bahaya,
                    keluhan, tindakan_konseling
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['pasien_id'], 
            $data['tanggal_kunjungan'], 
            $data['hari_ke'], 
            $data['kunjungan_ke'] ?? null,
            $data['tekanan_darah'] ?? null, 
            $data['suhu'] ?? null, 
            $data['nadi'] ?? null, 
            $data['pernapasan'] ?? null,
            $data['tfu'] ?? null, 
            $data['lokia'] ?? null, 
            $data['kondisi_perineum'] ?? null,
            $data['kontraksi_uterus'] ?? null, 
            $data['asi'] ?? null, 
            $data['laktasi'] ?? null, 
            $data['masalah_laktasi'] ?? null,
            $data['involusi_uterus'] ?? null, 
            $data['vitamin_a'] ?? null, 
            $data['tablet_fe'] ?? null,
            $data['konseling_kb'] ?? null, 
            $data['pemeriksaan_payudara'] ?? null, 
            $data['tanda_bahaya'] ?? null,
            $data['keluhan'] ?? null, 
            $data['tindakan_konseling'] ?? null
        ]);
        
        return $pdo->lastInsertId();
    }
    
    // Update data nifas
    public static function update($pdo, $id, $data) {
        $sql = "UPDATE kunjungan_nifas SET 
                    pasien_id = ?, tanggal_kunjungan = ?, hari_ke = ?, kunjungan_ke = ?,
                    tekanan_darah = ?, suhu = ?, nadi = ?, pernapasan = ?,
                    tfu = ?, lokia = ?, kondisi_perineum = ?,
                    kontraksi_uterus = ?, asi = ?, laktasi = ?, masalah_laktasi = ?,
                    involusi_uterus = ?, vitamin_a = ?, tablet_fe = ?,
                    konseling_kb = ?, pemeriksaan_payudara = ?, tanda_bahaya = ?,
                    keluhan = ?, tindakan_konseling = ?
                WHERE id = ?";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['pasien_id'], $data['tanggal_kunjungan'], $data['hari_ke'], $data['kunjungan_ke'] ?? null,
            $data['tekanan_darah'] ?? null, $data['suhu'] ?? null, $data['nadi'] ?? null, $data['pernapasan'] ?? null,
            $data['tfu'] ?? null, $data['lokia'] ?? null, $data['kondisi_perineum'] ?? null,
            $data['kontraksi_uterus'] ?? null, $data['asi'] ?? null, $data['laktasi'] ?? null, $data['masalah_laktasi'] ?? null,
            $data['involusi_uterus'] ?? null, $data['vitamin_a'] ?? null, $data['tablet_fe'] ?? null,
            $data['konseling_kb'] ?? null, $data['pemeriksaan_payudara'] ?? null, $data['tanda_bahaya'] ?? null,
            $data['keluhan'] ?? null, $data['tindakan_konseling'] ?? null, $id
        ]);
    }
    
    // Mengambil data nifas berdasarkan ID
    public static function findById($pdo, $id) {
        $stmt = $pdo->prepare("
            SELECT kn.*, p.nama_pasien, p.no_rm
            FROM kunjungan_nifas kn
            JOIN pasiens p ON kn.pasien_id = p.id
            WHERE kn.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Mengambil semua riwayat nifas berdasarkan ID Pasien
    public static function findByPasienId($pdo, $pasien_id) {
        $stmt = $pdo->prepare("
            SELECT * FROM kunjungan_nifas
            WHERE pasien_id = ?
            ORDER BY tanggal_kunjungan DESC
        ");
        $stmt->execute([$pasien_id]);
        return $stmt->fetchAll();
    }
    
    // Menghitung total kunjungan nifas
    public static function countAll($pdo) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM kunjungan_nifas");
        return $stmt->fetchColumn();
    }
    
    // Menghitung total kunjungan nifas bulan ini
    public static function countThisMonth($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM kunjungan_nifas
            WHERE MONTH(tanggal_kunjungan) = MONTH(CURRENT_DATE())
            AND YEAR(tanggal_kunjungan) = YEAR(CURRENT_DATE())
        ");
        return $stmt->fetchColumn();
    }
    
    // Menghitung kunjungan berdasarkan KF
    public static function countByKunjunganKe($pdo) {
        $stmt = $pdo->query("
            SELECT
                kunjungan_ke,
                COUNT(*) as jumlah
            FROM kunjungan_nifas
            WHERE kunjungan_ke IS NOT NULL
            GROUP BY kunjungan_ke
        ");
        return $stmt->fetchAll();
    }
    
    // Menghitung pemberian Vitamin A
    public static function countVitaminA($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM kunjungan_nifas
            WHERE vitamin_a = 'Sudah'
        ");
        return $stmt->fetchColumn();
    }
    
    // Menghitung pemberian Tablet Fe
    public static function countTabletFe($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM kunjungan_nifas
            WHERE tablet_fe = 'Sudah'
        ");
        return $stmt->fetchColumn();
    }
    
    // Menghitung konseling KB
    public static function getKBCounselingRate($pdo) {
        $stmt = $pdo->query("
            SELECT
                COUNT(*) as total,
                SUM(CASE WHEN konseling_kb IS NOT NULL AND konseling_kb != '' THEN 1 ELSE 0 END) as konseling_kb_count
            FROM kunjungan_nifas
        ");
        $result = $stmt->fetch();
        $total = $result['total'] ?? 0;
        $konseling = $result['konseling_kb_count'] ?? 0;
        return $total > 0 ? round(($konseling / $total) * 100, 1) : 0;
    }
    
    // Menghitung tanda bahaya nifas
    public static function countTandaBahaya($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM kunjungan_nifas
            WHERE tanda_bahaya IS NOT NULL AND tanda_bahaya != '' AND tanda_bahaya != 'Tidak Ada'
        ");
        return $stmt->fetchColumn();
    }
    
    // Statistik nifas
    public static function getNifasStatistics($pdo) {
        $stmt = $pdo->query("
            SELECT
                COUNT(*) as total_kunjungan,
                SUM(CASE WHEN kunjungan_ke = 'KF1' THEN 1 ELSE 0 END) as kf1_count,
                SUM(CASE WHEN kunjungan_ke = 'KF2' THEN 1 ELSE 0 END) as kf2_count,
                SUM(CASE WHEN kunjungan_ke = 'KF3' THEN 1 ELSE 0 END) as kf3_count,
                SUM(CASE WHEN kunjungan_ke = 'KF4' THEN 1 ELSE 0 END) as kf4_count,
                SUM(CASE WHEN vitamin_a = 'Sudah' THEN 1 ELSE 0 END) as vitamin_a_count,
                SUM(CASE WHEN tablet_fe = 'Sudah' THEN 1 ELSE 0 END) as tablet_fe_count,
                SUM(CASE WHEN asi = 'Lancar' THEN 1 ELSE 0 END) as asi_lancar_count,
                SUM(CASE WHEN involusi_uterus = 'Tidak Sesuai' THEN 1 ELSE 0 END) as subinvolusi_count
            FROM kunjungan_nifas
        ");
        return $stmt->fetch();
    }
    
    // Hapus data nifas
    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM kunjungan_nifas WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
