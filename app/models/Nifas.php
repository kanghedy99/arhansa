<?php
// File: app/models/Nifas.php
// Model untuk mengelola data kunjungan Post-Natal Care (PNC).

class Nifas {

    // Mengambil semua data kunjungan nifas dengan join ke tabel pasien
    public static function getAll($pdo) {
        $stmt = $pdo->prepare("
            SELECT 
                kn.*, p.nama_pasien, p.no_rm 
            FROM 
                kunjungan_nifas kn
            JOIN 
                pasiens p ON kn.pasien_id = p.id
            ORDER BY 
                kn.tanggal_kunjungan DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Menyimpan data kunjungan nifas baru
    public static function create($pdo, $data) {
        $sql = "INSERT INTO kunjungan_nifas (
                    pasien_id, tanggal_kunjungan, hari_ke, tekanan_darah, suhu, 
                    nadi, pernapasan, tfu, lokia, kondisi_perineum, 
                    keluhan, tindakan_konseling
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['pasien_id'], $data['tanggal_kunjungan'], $data['hari_ke'],
            $data['tekanan_darah'], $data['suhu'] ?: null, $data['nadi'] ?: null,
            $data['pernapasan'] ?: null, $data['tfu'], $data['lokia'],
            $data['kondisi_perineum'], $data['keluhan'], $data['tindakan_konseling']
        ]);
        return $pdo->lastInsertId();
    }
    
    // Mengambil semua riwayat nifas berdasarkan ID Pasien
    public static function findByPasienId($pdo, $pasien_id) {
        $stmt = $pdo->prepare("SELECT * FROM kunjungan_nifas WHERE pasien_id = ? ORDER BY tanggal_kunjungan DESC");
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
        $stmt = $pdo->query("SELECT COUNT(*) FROM kunjungan_nifas WHERE MONTH(tanggal_kunjungan) = MONTH(CURRENT_DATE()) AND YEAR(tanggal_kunjungan) = YEAR(CURRENT_DATE())");
        return $stmt->fetchColumn();
    }

    // Mengambil data nifas berdasarkan ID
    public static function findById($pdo, $id) {
        $stmt = $pdo->prepare("
            SELECT 
                kn.*, p.nama_pasien, p.no_rm 
            FROM 
                kunjungan_nifas kn
            JOIN 
                pasiens p ON kn.pasien_id = p.id
            WHERE 
                kn.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Update data nifas
    public static function update($pdo, $id, $data) {
        $sql = "UPDATE kunjungan_nifas SET 
                    pasien_id = ?, tanggal_kunjungan = ?, hari_ke = ?, tekanan_darah = ?, suhu = ?, 
                    nadi = ?, pernapasan = ?, tfu = ?, lokia = ?, kondisi_perineum = ?, 
                    keluhan = ?, tindakan_konseling = ?
                WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['pasien_id'], $data['tanggal_kunjungan'], $data['hari_ke'],
            $data['tekanan_darah'], $data['suhu'] ?: null, $data['nadi'] ?: null,
            $data['pernapasan'] ?: null, $data['tfu'], $data['lokia'],
            $data['kondisi_perineum'], $data['keluhan'], $data['tindakan_konseling'], $id
        ]);
    }

    // Hapus data nifas
    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM kunjungan_nifas WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
