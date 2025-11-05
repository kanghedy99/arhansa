<?php
// File: app/models/KunjunganBayi.php (Baru)
// Model untuk mengelola data kunjungan bayi.

class KunjunganBayi {

    // Mengambil semua data kunjungan bayi dengan join ke tabel bayi dan pasien (ibu)
    public static function getAll($pdo) {
        $stmt = $pdo->prepare("
            SELECT 
                kb.*, b.nama_bayi, p.nama_pasien as nama_ibu
            FROM 
                kunjungan_bayi kb
            JOIN 
                bayis b ON kb.bayi_id = b.id
            JOIN
                pasiens p ON b.pasien_id = p.id
            ORDER BY 
                kb.tanggal_kunjungan DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Menyimpan data kunjungan bayi baru
    public static function create($pdo, $data) {
        $sql = "INSERT INTO kunjungan_bayi (
                    bayi_id, tanggal_kunjungan, berat_badan, panjang_badan, 
                    lingkar_kepala, jenis_imunisasi, catatan_klinis, pemberi_layanan
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['bayi_id'], 
            $data['tanggal_kunjungan'], 
            $data['berat_badan'] ?: null,
            $data['panjang_badan'] ?: null,
            $data['lingkar_kepala'] ?: null,
            $data['jenis_imunisasi'],
            $data['catatan_klinis'],
            $data['pemberi_layanan']
        ]);
        return $pdo->lastInsertId();
    }

    // Menghitung total kunjungan bayi
    public static function countAll($pdo) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM kunjungan_bayi");
        return $stmt->fetchColumn();
    }

    // Menghitung total kunjungan bayi bulan ini
    public static function countThisMonth($pdo) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM kunjungan_bayi WHERE MONTH(tanggal_kunjungan) = MONTH(CURRENT_DATE()) AND YEAR(tanggal_kunjungan) = YEAR(CURRENT_DATE())");
        return $stmt->fetchColumn();
    }

    // Mengambil data kunjungan bayi berdasarkan ID
    public static function findById($pdo, $id) {
        $stmt = $pdo->prepare("
            SELECT 
                kb.*, b.nama_bayi, p.nama_pasien as nama_ibu, p.no_rm
            FROM 
                kunjungan_bayi kb
            JOIN 
                bayis b ON kb.bayi_id = b.id
            JOIN
                pasiens p ON b.pasien_id = p.id
            WHERE 
                kb.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Mengambil riwayat kunjungan bayi berdasarkan ID Bayi
    public static function findByBayiId($pdo, $bayi_id) {
        $stmt = $pdo->prepare("SELECT * FROM kunjungan_bayi WHERE bayi_id = ? ORDER BY tanggal_kunjungan DESC");
        $stmt->execute([$bayi_id]);
        return $stmt->fetchAll();
    }

    // Update data kunjungan bayi
    public static function update($pdo, $id, $data) {
        $sql = "UPDATE kunjungan_bayi SET 
                    bayi_id = ?, tanggal_kunjungan = ?, berat_badan = ?, panjang_badan = ?, 
                    lingkar_kepala = ?, jenis_imunisasi = ?, catatan_klinis = ?, pemberi_layanan = ?
                WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['bayi_id'], 
            $data['tanggal_kunjungan'], 
            $data['berat_badan'] ?: null,
            $data['panjang_badan'] ?: null,
            $data['lingkar_kepala'] ?: null,
            $data['jenis_imunisasi'],
            $data['catatan_klinis'],
            $data['pemberi_layanan'], $id
        ]);
    }

    // Hapus data kunjungan bayi
    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM kunjungan_bayi WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
