<?php
// File: app/models/Anc.php
// Model untuk mengelola data kunjungan Antenatal Care (ANC) - Updated sesuai Standar Kemenkes RI

class Anc {

    // Mengambil semua data kunjungan ANC dengan join ke tabel pasien
    public static function getAll($pdo, $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $stmt = $pdo->prepare("
            SELECT 
                ka.*, 
                p.nama_pasien, 
                p.no_rm,
                p.gravida_paritas,
                CASE 
                    WHEN ka.kategori_risiko = 'Rendah' THEN 'KRR'
                    WHEN ka.kategori_risiko = 'Tinggi' THEN 'KRT'
                    WHEN ka.kategori_risiko = 'Sangat Tinggi' THEN 'KRST'
                    ELSE '-'
                END as kategori_risiko_label
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

    // Menyimpan data kunjungan ANC baru dengan kolom tambahan (Pemeriksaan 10T)
    public static function create($pdo, $data) {
        $sql = "INSERT INTO kunjungan_anc (
                    pasien_id, 
                    tanggal_kunjungan, 
                    usia_kehamilan, 
                    kunjungan_ke,
                    keluhan, 
                    berat_badan, 
                    tinggi_badan, 
                    tekanan_darah, 
                    lila, 
                    tfu, 
                    djj, 
                    presentasi_janin, 
                    hasil_lab, 
                    diagnosis, 
                    terapi, 
                    konseling, 
                    jadwal_kunjungan_ulang,
                    tablet_fe,
                    imunisasi_tt,
                    protein_urin,
                    hiv_test,
                    sifilis_test,
                    hepatitis_b_test,
                    hb_hemoglobin,
                    golongan_darah_suami,
                    skor_poedji_rochjati,
                    kategori_risiko
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['pasien_id'], 
            $data['tanggal_kunjungan'], 
            $data['usia_kehamilan'],
            $data['kunjungan_ke'] ?? null,
            $data['keluhan'] ?? null, 
            $data['berat_badan'] ?? null,
            $data['tinggi_badan'] ?? null, 
            $data['tekanan_darah'] ?? null, 
            $data['lila'] ?? null, 
            $data['tfu'] ?? null, 
            $data['djj'] ?? null, 
            $data['presentasi_janin'] ?? null,
            $data['hasil_lab'] ?? null, 
            $data['diagnosis'] ?? null, 
            $data['terapi'] ?? null, 
            $data['konseling'] ?? null, 
            $data['jadwal_kunjungan_ulang'] ?? null,
            // Kolom baru - Pemeriksaan 10T
            $data['tablet_fe'] ?? null,
            $data['imunisasi_tt'] ?? null,
            $data['protein_urin'] ?? null,
            $data['hiv_test'] ?? null,
            $data['sifilis_test'] ?? null,
            $data['hepatitis_b_test'] ?? null,
            $data['hb_hemoglobin'] ?? null,
            $data['golongan_darah_suami'] ?? null,
            $data['skor_poedji_rochjati'] ?? null,
            $data['kategori_risiko'] ?? null
        ]);
        
        return $pdo->lastInsertId();
    }
    
    // Update data kunjungan ANC
    public static function update($pdo, $id, $data) {
        $sql = "UPDATE kunjungan_anc SET
                    pasien_id = ?,
                    tanggal_kunjungan = ?,
                    usia_kehamilan = ?,
                    kunjungan_ke = ?,
                    keluhan = ?,
                    berat_badan = ?,
                    tinggi_badan = ?,
                    tekanan_darah = ?,
                    lila = ?,
                    tfu = ?,
                    djj = ?,
                    presentasi_janin = ?,
                    hasil_lab = ?,
                    diagnosis = ?,
                    terapi = ?,
                    konseling = ?,
                    jadwal_kunjungan_ulang = ?,
                    tablet_fe = ?,
                    imunisasi_tt = ?,
                    protein_urin = ?,
                    hiv_test = ?,
                    sifilis_test = ?,
                    hepatitis_b_test = ?,
                    hb_hemoglobin = ?,
                    golongan_darah_suami = ?,
                    skor_poedji_rochjati = ?,
                    kategori_risiko = ?
                WHERE id = ?";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['pasien_id'],
            $data['tanggal_kunjungan'],
            $data['usia_kehamilan'],
            $data['kunjungan_ke'] ?? null,
            $data['keluhan'] ?? null,
            $data['berat_badan'] ?? null,
            $data['tinggi_badan'] ?? null,
            $data['tekanan_darah'] ?? null,
            $data['lila'] ?? null,
            $data['tfu'] ?? null,
            $data['djj'] ?? null,
            $data['presentasi_janin'] ?? null,
            $data['hasil_lab'] ?? null,
            $data['diagnosis'] ?? null,
            $data['terapi'] ?? null,
            $data['konseling'] ?? null,
            $data['jadwal_kunjungan_ulang'] ?? null,
            $data['tablet_fe'] ?? null,
            $data['imunisasi_tt'] ?? null,
            $data['protein_urin'] ?? null,
            $data['hiv_test'] ?? null,
            $data['sifilis_test'] ?? null,
            $data['hepatitis_b_test'] ?? null,
            $data['hb_hemoglobin'] ?? null,
            $data['golongan_darah_suami'] ?? null,
            $data['skor_poedji_rochjati'] ?? null,
            $data['kategori_risiko'] ?? null,
            $id
        ]);
    }
    
    // Mengambil data ANC berdasarkan ID
    public static function findById($pdo, $id) {
        $stmt = $pdo->prepare("
            SELECT ka.*, p.nama_pasien, p.no_rm 
            FROM kunjungan_anc ka
            JOIN pasiens p ON ka.pasien_id = p.id
            WHERE ka.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Mengambil semua riwayat ANC berdasarkan ID Pasien
    public static function findByPasienId($pdo, $pasien_id) {
        $stmt = $pdo->prepare("
            SELECT * FROM kunjungan_anc 
            WHERE pasien_id = ? 
            ORDER BY tanggal_kunjungan DESC
        ");
        $stmt->execute([$pasien_id]);
        return $stmt->fetchAll();
    }
    
    // Menghitung total kunjungan ANC bulan ini
    public static function countThisMonth($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM kunjungan_anc 
            WHERE MONTH(tanggal_kunjungan) = MONTH(CURRENT_DATE()) 
            AND YEAR(tanggal_kunjungan) = YEAR(CURRENT_DATE())
        ");
        return $stmt->fetchColumn();
    }
    
    // Menghitung jumlah ibu hamil dengan risiko tinggi
    public static function countHighRisk($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(DISTINCT pasien_id) 
            FROM kunjungan_anc 
            WHERE kategori_risiko IN ('Tinggi', 'Sangat Tinggi')
        ");
        return $stmt->fetchColumn();
    }
    
    // Mengambil kunjungan ANC terakhir pasien
    public static function getLastVisit($pdo, $pasien_id) {
        $stmt = $pdo->prepare("
            SELECT * FROM kunjungan_anc 
            WHERE pasien_id = ? 
            ORDER BY tanggal_kunjungan DESC 
            LIMIT 1
        ");
        $stmt->execute([$pasien_id]);
        return $stmt->fetch();
    }
    
    // Menghitung rata-rata kunjungan ANC per pasien
    public static function getAverageVisits($pdo) {
        $stmt = $pdo->query("
            SELECT AVG(visit_count) as avg_visits
            FROM (
                SELECT pasien_id, COUNT(*) as visit_count
                FROM kunjungan_anc
                GROUP BY pasien_id
            ) as subquery
        ");
        $result = $stmt->fetch();
        return round($result['avg_visits'] ?? 0, 1);
    }
    
    // Mengambil statistik pemeriksaan 10T
    public static function get10TStatistics($pdo) {
        $stmt = $pdo->query("
            SELECT 
                COUNT(*) as total_kunjungan,
                SUM(CASE WHEN tablet_fe IS NOT NULL AND tablet_fe != '' THEN 1 ELSE 0 END) as tablet_fe_count,
                SUM(CASE WHEN imunisasi_tt IS NOT NULL AND imunisasi_tt != '' THEN 1 ELSE 0 END) as imunisasi_tt_count,
                SUM(CASE WHEN protein_urin IS NOT NULL AND protein_urin != '' THEN 1 ELSE 0 END) as protein_urin_count,
                SUM(CASE WHEN hiv_test IS NOT NULL AND hiv_test != '' THEN 1 ELSE 0 END) as hiv_test_count,
                SUM(CASE WHEN sifilis_test IS NOT NULL AND sifilis_test != '' THEN 1 ELSE 0 END) as sifilis_test_count,
                SUM(CASE WHEN hepatitis_b_test IS NOT NULL AND hepatitis_b_test != '' THEN 1 ELSE 0 END) as hepatitis_b_test_count
            FROM kunjungan_anc
        ");
        return $stmt->fetch();
    }
    
    // Delete kunjungan ANC
    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM kunjungan_anc WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
