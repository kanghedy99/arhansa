<?php
// File: app/models/Inc.php
// Model untuk mengelola data kunjungan Intrapartum Care (INC) - Updated sesuai Standar APN Kemenkes RI

class Inc {

    // Mengambil semua data kunjungan INC dengan join ke tabel pasien
    public static function getAll($pdo, $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $stmt = $pdo->prepare("
            SELECT 
                ki.*, 
                p.nama_pasien, 
                p.no_rm,
                p.gravida_paritas
            FROM 
                kunjungan_inc ki
            JOIN 
                pasiens p ON ki.pasien_id = p.id
            ORDER BY 
                ki.tanggal_kunjungan DESC 
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Menghitung total kunjungan INC
    public static function countAll($pdo) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM kunjungan_inc");
        return $stmt->fetchColumn();
    }

    // Menyimpan data kunjungan INC baru dengan kolom tambahan (APN)
    public static function create($pdo, $data) {
        $sql = "INSERT INTO kunjungan_inc (
                    pasien_id, tanggal_kunjungan, jam_masuk, 
                    keluhan, riwayat_kehamilan, 
                    his, djj, pembukaan_serviks, penurunan_kepala, ketuban, 
                    tekanan_darah, nadi, suhu, pernapasan, 
                    diagnosis, tindakan, obat_diberikan, catatan_khusus, 
                    jam_keluar, kondisi_ibu, kondisi_bayi,
                    kala_persalinan, lama_kala_1, lama_kala_2, lama_kala_3, lama_kala_4,
                    jenis_persalinan, penolong_persalinan, pendamping_persalinan,
                    komplikasi, perdarahan, plasenta_lengkap, berat_plasenta,
                    robekan_perineum, episiotomi, penjahitan,
                    oksitosin, waktu_oksitosin,
                    imd, lama_imd, vitamin_k_bayi, salep_mata_bayi, hb0_bayi
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['pasien_id'], 
            $data['tanggal_kunjungan'], 
            $data['jam_masuk'], 
            $data['keluhan'] ?? null, 
            $data['riwayat_kehamilan'] ?? null,
            $data['his'] ?? null, 
            $data['djj'] ?? null, 
            $data['pembukaan_serviks'] ?? null, 
            $data['penurunan_kepala'] ?? null, 
            $data['ketuban'] ?? null,
            $data['tekanan_darah'] ?? null, 
            $data['nadi'] ?? null, 
            $data['suhu'] ?? null, 
            $data['pernapasan'] ?? null, 
            $data['diagnosis'] ?? null,
            $data['tindakan'] ?? null, 
            $data['obat_diberikan'] ?? null, 
            $data['catatan_khusus'] ?? null, 
            $data['jam_keluar'] ?? null, 
            $data['kondisi_ibu'] ?? null, 
            $data['kondisi_bayi'] ?? null,
            // Kolom baru - Kala Persalinan
            $data['kala_persalinan'] ?? null,
            $data['lama_kala_1'] ?? null,
            $data['lama_kala_2'] ?? null,
            $data['lama_kala_3'] ?? null,
            $data['lama_kala_4'] ?? null,
            $data['jenis_persalinan'] ?? null,
            $data['penolong_persalinan'] ?? null,
            $data['pendamping_persalinan'] ?? null,
            // Kolom baru - Manajemen Aktif Kala III
            $data['komplikasi'] ?? null,
            $data['perdarahan'] ?? null,
            $data['plasenta_lengkap'] ?? null,
            $data['berat_plasenta'] ?? null,
            $data['robekan_perineum'] ?? null,
            $data['episiotomi'] ?? null,
            $data['penjahitan'] ?? null,
            $data['oksitosin'] ?? null,
            $data['waktu_oksitosin'] ?? null,
            // Kolom baru - Pelayanan Neonatal
            $data['imd'] ?? null,
            $data['lama_imd'] ?? null,
            $data['vitamin_k_bayi'] ?? null,
            $data['salep_mata_bayi'] ?? null,
            $data['hb0_bayi'] ?? null
        ]);
        
        return $pdo->lastInsertId();
    }
    
    // Update data INC
    public static function update($pdo, $id, $data) {
        $sql = "UPDATE kunjungan_inc SET 
                    pasien_id = ?, tanggal_kunjungan = ?, jam_masuk = ?, 
                    keluhan = ?, riwayat_kehamilan = ?, 
                    his = ?, djj = ?, pembukaan_serviks = ?, penurunan_kepala = ?, ketuban = ?, 
                    tekanan_darah = ?, nadi = ?, suhu = ?, pernapasan = ?, 
                    diagnosis = ?, tindakan = ?, obat_diberikan = ?, catatan_khusus = ?, 
                    jam_keluar = ?, kondisi_ibu = ?, kondisi_bayi = ?,
                    kala_persalinan = ?, lama_kala_1 = ?, lama_kala_2 = ?, lama_kala_3 = ?, lama_kala_4 = ?,
                    jenis_persalinan = ?, penolong_persalinan = ?, pendamping_persalinan = ?,
                    komplikasi = ?, perdarahan = ?, plasenta_lengkap = ?, berat_plasenta = ?,
                    robekan_perineum = ?, episiotomi = ?, penjahitan = ?,
                    oksitosin = ?, waktu_oksitosin = ?,
                    imd = ?, lama_imd = ?, vitamin_k_bayi = ?, salep_mata_bayi = ?, hb0_bayi = ?
                WHERE id = ?";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['pasien_id'], $data['tanggal_kunjungan'], $data['jam_masuk'],
            $data['keluhan'] ?? null, $data['riwayat_kehamilan'] ?? null,
            $data['his'] ?? null, $data['djj'] ?? null, $data['pembukaan_serviks'] ?? null,
            $data['penurunan_kepala'] ?? null, $data['ketuban'] ?? null,
            $data['tekanan_darah'] ?? null, $data['nadi'] ?? null, $data['suhu'] ?? null,
            $data['pernapasan'] ?? null, $data['diagnosis'] ?? null, $data['tindakan'] ?? null,
            $data['obat_diberikan'] ?? null, $data['catatan_khusus'] ?? null,
            $data['jam_keluar'] ?? null, $data['kondisi_ibu'] ?? null, $data['kondisi_bayi'] ?? null,
            $data['kala_persalinan'] ?? null, $data['lama_kala_1'] ?? null,
            $data['lama_kala_2'] ?? null, $data['lama_kala_3'] ?? null, $data['lama_kala_4'] ?? null,
            $data['jenis_persalinan'] ?? null, $data['penolong_persalinan'] ?? null,
            $data['pendamping_persalinan'] ?? null, $data['komplikasi'] ?? null,
            $data['perdarahan'] ?? null, $data['plasenta_lengkap'] ?? null,
            $data['berat_plasenta'] ?? null, $data['robekan_perineum'] ?? null,
            $data['episiotomi'] ?? null, $data['penjahitan'] ?? null,
            $data['oksitosin'] ?? null, $data['waktu_oksitosin'] ?? null,
            $data['imd'] ?? null, $data['lama_imd'] ?? null,
            $data['vitamin_k_bayi'] ?? null, $data['salep_mata_bayi'] ?? null,
            $data['hb0_bayi'] ?? null, $id
        ]);
    }
    
    // Mengambil data INC berdasarkan ID
    public static function findById($pdo, $id) {
        $stmt = $pdo->prepare("
            SELECT ki.*, p.nama_pasien, p.no_rm 
            FROM kunjungan_inc ki
            JOIN pasiens p ON ki.pasien_id = p.id
            WHERE ki.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Mengambil semua riwayat INC berdasarkan ID Pasien
    public static function findByPasienId($pdo, $pasien_id) {
        $stmt = $pdo->prepare("
            SELECT * FROM kunjungan_inc 
            WHERE pasien_id = ? 
            ORDER BY tanggal_kunjungan DESC
        ");
        $stmt->execute([$pasien_id]);
        return $stmt->fetchAll();
    }
    
    // Menghitung total kunjungan INC bulan ini
    public static function countThisMonth($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM kunjungan_inc 
            WHERE MONTH(tanggal_kunjungan) = MONTH(CURRENT_DATE()) 
            AND YEAR(tanggal_kunjungan) = YEAR(CURRENT_DATE())
        ");
        return $stmt->fetchColumn();
    }
    
    // Menghitung persalinan berdasarkan jenis
    public static function countByJenisPersalinan($pdo) {
        $stmt = $pdo->query("
            SELECT 
                jenis_persalinan,
                COUNT(*) as jumlah
            FROM kunjungan_inc
            WHERE jenis_persalinan IS NOT NULL
            GROUP BY jenis_persalinan
        ");
        return $stmt->fetchAll();
    }
    
    // Menghitung IMD yang dilakukan
    public static function countIMD($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM kunjungan_inc 
            WHERE imd = 'Ya'
        ");
        return $stmt->fetchColumn();
    }
    
    // Menghitung perdarahan post partum (>500ml)
    public static function countPostPartumHemorrhage($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM kunjungan_inc 
            WHERE perdarahan > 500
        ");
        return $stmt->fetchColumn();
    }
    
    // Statistik persalinan
    public static function getDeliveryStatistics($pdo) {
        $stmt = $pdo->query("
            SELECT 
                COUNT(*) as total_persalinan,
                SUM(CASE WHEN jenis_persalinan = 'Normal' THEN 1 ELSE 0 END) as normal,
                SUM(CASE WHEN jenis_persalinan = 'SC' THEN 1 ELSE 0 END) as sc,
                SUM(CASE WHEN jenis_persalinan LIKE '%Vakum%' THEN 1 ELSE 0 END) as vakum,
                SUM(CASE WHEN imd = 'Ya' THEN 1 ELSE 0 END) as imd_count,
                SUM(CASE WHEN perdarahan > 500 THEN 1 ELSE 0 END) as pph_count,
                SUM(CASE WHEN robekan_perineum LIKE '%Derajat III%' OR robekan_perineum LIKE '%Derajat IV%' THEN 1 ELSE 0 END) as robekan_berat
            FROM kunjungan_inc
        ");
        return $stmt->fetch();
    }
    
    // Hapus data INC
    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM kunjungan_inc WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
