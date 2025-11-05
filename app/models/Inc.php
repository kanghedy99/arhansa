<?php
// File: app/models/Inc.php
// Model untuk mengelola data kunjungan Intrapartum Care (INC).

class Inc {

    // Mengambil semua data kunjungan INC dengan join ke tabel pasien
    public static function getAll($pdo, $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $stmt = $pdo->prepare("
            SELECT 
                ki.*, p.nama_pasien, p.no_rm 
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

    // Menyimpan data kunjungan INC baru
    public static function create($pdo, $data) {
        $sql = "INSERT INTO kunjungan_inc (
                    pasien_id, tanggal_kunjungan, jam_masuk, keluhan, riwayat_kehamilan, 
                    his, djj, pembukaan_serviks, penurunan_kepala, ketuban, 
                    tekanan_darah, nadi, suhu, pernapasan, diagnosis, 
                    tindakan, obat_diberikan, catatan_khusus, jam_keluar, kondisi_ibu, kondisi_bayi
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['pasien_id'], $data['tanggal_kunjungan'], $data['jam_masuk'], $data['keluhan'], $data['riwayat_kehamilan'],
            $data['his'], $data['djj'], $data['pembukaan_serviks'], $data['penurunan_kepala'], $data['ketuban'],
            $data['tekanan_darah'], $data['nadi'], $data['suhu'], $data['pernapasan'], $data['diagnosis'],
            $data['tindakan'], $data['obat_diberikan'], $data['catatan_khusus'], $data['jam_keluar'] ?: null, 
            $data['kondisi_ibu'] ?: null, $data['kondisi_bayi'] ?: null
        ]);
        return $pdo->lastInsertId();
    }
    
    // Mengambil semua riwayat INC berdasarkan ID Pasien
    public static function findByPasienId($pdo, $pasien_id) {
        $stmt = $pdo->prepare("SELECT * FROM kunjungan_inc WHERE pasien_id = ? ORDER BY tanggal_kunjungan DESC");
        $stmt->execute([$pasien_id]);
        return $stmt->fetchAll();
    }
    
    // Menghitung total kunjungan INC bulan ini
    public static function countThisMonth($pdo) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM kunjungan_inc WHERE MONTH(tanggal_kunjungan) = MONTH(CURRENT_DATE()) AND YEAR(tanggal_kunjungan) = YEAR(CURRENT_DATE())");
        return $stmt->fetchColumn();
    }

    // Mengambil data INC berdasarkan ID
    public static function findById($pdo, $id) {
        $stmt = $pdo->prepare("
            SELECT 
                ki.*, p.nama_pasien, p.no_rm 
            FROM 
                kunjungan_inc ki
            JOIN 
                pasiens p ON ki.pasien_id = p.id
            WHERE 
                ki.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Update data INC
    public static function update($pdo, $id, $data) {
        $sql = "UPDATE kunjungan_inc SET 
                    pasien_id = ?, tanggal_kunjungan = ?, jam_masuk = ?, keluhan = ?, riwayat_kehamilan = ?, 
                    his = ?, djj = ?, pembukaan_serviks = ?, penurunan_kepala = ?, ketuban = ?, 
                    tekanan_darah = ?, nadi = ?, suhu = ?, pernapasan = ?, diagnosis = ?, 
                    tindakan = ?, obat_diberikan = ?, catatan_khusus = ?, jam_keluar = ?, kondisi_ibu = ?, kondisi_bayi = ?
                WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['pasien_id'], $data['tanggal_kunjungan'], $data['jam_masuk'], $data['keluhan'], $data['riwayat_kehamilan'],
            $data['his'], $data['djj'], $data['pembukaan_serviks'], $data['penurunan_kepala'], $data['ketuban'],
            $data['tekanan_darah'], $data['nadi'], $data['suhu'], $data['pernapasan'], $data['diagnosis'],
            $data['tindakan'], $data['obat_diberikan'], $data['catatan_khusus'], $data['jam_keluar'] ?: null, 
            $data['kondisi_ibu'] ?: null, $data['kondisi_bayi'] ?: null, $id
        ]);
    }

    // Hapus data INC
    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM kunjungan_inc WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
