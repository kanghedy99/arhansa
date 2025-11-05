<?php
// File: app/models/Bayi.php (Final)
// Model untuk mengelola data bayi secara lengkap.

class Bayi {
    
    // Mengambil semua data bayi dengan join ke tabel pasien (ibu)
    public static function getAll($pdo) {
        $stmt = $pdo->prepare("
            SELECT 
                b.*, p.nama_pasien as nama_ibu, p.no_rm 
            FROM 
                bayis b
            JOIN 
                pasiens p ON b.pasien_id = p.id
            ORDER BY 
                b.tanggal_lahir DESC, b.nama_bayi ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Mencari data bayi berdasarkan ID
    public static function findById($pdo, $id) {
        $stmt = $pdo->prepare("
            SELECT 
                b.*, p.nama_pasien as nama_ibu, p.no_rm
            FROM 
                bayis b
            JOIN 
                pasiens p ON b.pasien_id = p.id
            WHERE 
                b.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Menyimpan data bayi baru
    public static function create($pdo, $data) {
        $sql = "INSERT INTO bayis (pasien_id, nama_bayi, tanggal_lahir, jam_lahir, jenis_kelamin, berat_lahir, panjang_lahir, lingkar_kepala, catatan_kelahiran) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['pasien_id'], $data['nama_bayi'], $data['tanggal_lahir'],
            $data['jam_lahir'] ?: null, $data['jenis_kelamin'],
            $data['berat_lahir'] ?: null, $data['panjang_lahir'] ?: null,
            $data['lingkar_kepala'] ?: null, $data['catatan_kelahiran']
        ]);
        return $pdo->lastInsertId();
    }

    // Memperbarui data bayi
    public static function update($pdo, $id, $data) {
        $sql = "UPDATE bayis SET 
                    pasien_id = ?, nama_bayi = ?, tanggal_lahir = ?, jam_lahir = ?, jenis_kelamin = ?, 
                    berat_lahir = ?, panjang_lahir = ?, lingkar_kepala = ?, catatan_kelahiran = ?
                WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['pasien_id'], $data['nama_bayi'], $data['tanggal_lahir'],
            $data['jam_lahir'] ?: null, $data['jenis_kelamin'],
            $data['berat_lahir'] ?: null, $data['panjang_lahir'] ?: null,
            $data['lingkar_kepala'] ?: null, $data['catatan_kelahiran'], $id
        ]);
        return $stmt->rowCount();
    }

    // Menghapus data bayi
    public static function delete($pdo, $id) {
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM imunisasi WHERE bayi_id = ?");
        $stmtCheck->execute([$id]);
        if ($stmtCheck->fetchColumn() > 0) {
            return false;
        }
        $stmt = $pdo->prepare("DELETE FROM bayis WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
    
    // Tambahkan fungsi ini di dalam class Bayi di file app/models/Bayi.php
public static function getAllByPasienId($pdo, $pasien_id) {
    $stmt = $pdo->prepare("SELECT * FROM bayis WHERE pasien_id = ? ORDER BY tanggal_lahir DESC");
    $stmt->execute([$pasien_id]);
    return $stmt->fetchAll();
}

public static function getGrowthDataById($pdo, $bayi_id) {
        $stmt = $pdo->prepare("
            SELECT 
                tanggal_kunjungan, 
                berat_badan, 
                panjang_badan, 
                lingkar_kepala
            FROM 
                kunjungan_bayi -- Mengambil dari tabel baru
            WHERE 
                bayi_id = ? 
                AND (berat_badan IS NOT NULL OR panjang_badan IS NOT NULL OR lingkar_kepala IS NOT NULL)
            ORDER BY 
                tanggal_kunjungan ASC
        ");
        $stmt->execute([$bayi_id]);
        return $stmt->fetchAll();
    }
}
?>
