<?php
// File: app/models/Imunisasi.php
// Model untuk mengelola data layanan Imunisasi.

class Imunisasi {
    
    // Mengambil semua data imunisasi dengan join ke tabel bayi dan pasien (ibu)
    public static function getAll($pdo, $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $stmt = $pdo->prepare("
            SELECT 
                i.*, b.nama_bayi, p.nama_pasien as nama_ibu, p.no_rm
            FROM 
                imunisasi i
            JOIN 
                bayis b ON i.bayi_id = b.id
            JOIN
                pasiens p ON b.pasien_id = p.id
            ORDER BY 
                i.tanggal_imunisasi DESC 
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Menghitung total data imunisasi
    public static function countAll($pdo) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM imunisasi");
        return $stmt->fetchColumn();
    }

    // Menyimpan data imunisasi baru (dengan data pertumbuhan)
    public static function create($pdo, $data) {
        $sql = "INSERT INTO imunisasi (
                    bayi_id, tanggal_imunisasi, jenis_imunisasi, catatan, 
                    pemberi_imunisasi, berat_badan, panjang_badan, lingkar_kepala
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['bayi_id'], 
            $data['tanggal_imunisasi'], 
            $data['jenis_imunisasi'],
            $data['catatan'],
            $data['pemberi_imunisasi'],
            $data['berat_badan'] ?: null,      // Simpan data pertumbuhan
            $data['panjang_badan'] ?: null,     // Simpan data pertumbuhan
            $data['lingkar_kepala'] ?: null     // Simpan data pertumbuhan
        ]);
        return $pdo->lastInsertId();
    }
    
    // FUNGSI BARU: Mengambil riwayat imunisasi semua anak dari seorang ibu
    public static function findByIbuId($pdo, $ibu_id) {
        $stmt = $pdo->prepare("
            SELECT i.*, b.nama_bayi
            FROM imunisasi i
            JOIN bayis b ON i.bayi_id = b.id
            WHERE b.pasien_id = ?
            ORDER BY b.nama_bayi, i.tanggal_imunisasi ASC
        ");
        $stmt->execute([$ibu_id]);
        return $stmt->fetchAll();
    }
    
    // FUNGSI BARU: Membuat record imunisasi dari data kunjungan bayi
    public static function createFromBabyVisit($pdo, $kunjungan_data) {
        // Hanya buat record imunisasi jika ada jenis imunisasi yang diberikan
        if (empty($kunjungan_data['jenis_imunisasi'])) {
            return null;
        }
        
        $sql = "INSERT INTO imunisasi (
                    bayi_id, tanggal_imunisasi, jenis_imunisasi, catatan, 
                    pemberi_imunisasi, berat_badan, panjang_badan, lingkar_kepala
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $kunjungan_data['bayi_id'], 
            $kunjungan_data['tanggal_kunjungan'], // Gunakan tanggal kunjungan sebagai tanggal imunisasi
            $kunjungan_data['jenis_imunisasi'],
            $kunjungan_data['catatan_klinis'] ?: 'Imunisasi diberikan saat kunjungan bayi',
            $kunjungan_data['pemberi_layanan'],
            $kunjungan_data['berat_badan'] ?: null,
            $kunjungan_data['panjang_badan'] ?: null,
            $kunjungan_data['lingkar_kepala'] ?: null
        ]);
        return $pdo->lastInsertId();
    }
    
    
}
?>
