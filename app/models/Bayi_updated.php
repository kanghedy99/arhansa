<?php
// File: app/models/Bayi.php
// Model untuk mengelola data bayi secara lengkap - Updated sesuai Standar Neonatal Esensial Kemenkes RI

class Bayi {

    // Mengambil semua data bayi dengan join ke tabel pasien (ibu)
    public static function getAll($pdo, $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $stmt = $pdo->prepare("
            SELECT
                b.*,
                p.nama_pasien as nama_ibu,
                p.no_rm,
                p.gravida_paritas
            FROM
                bayis b
            JOIN
                pasiens p ON b.pasien_id = p.id
            ORDER BY
                b.tanggal_lahir DESC, b.nama_bayi ASC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
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

    // Menyimpan data bayi baru dengan kolom tambahan (APGAR & Neonatal)
    public static function create($pdo, $data) {
        $sql = "INSERT INTO bayis (
                    pasien_id, nama_bayi, tanggal_lahir, jam_lahir, jenis_kelamin,
                    berat_lahir, panjang_lahir, lingkar_kepala,
                    apgar_1_menit, apgar_5_menit, kondisi_lahir,
                    resusitasi, jenis_resusitasi,
                    vitamin_k1, salep_mata, hb0_imunisasi, waktu_hb0,
                    imd, lama_imd, kelainan_kongenital,
                    golongan_darah, anak_ke, catatan_kelahiran
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['pasien_id'],
            $data['nama_bayi'],
            $data['tanggal_lahir'],
            $data['jam_lahir'] ?? null,
            $data['jenis_kelamin'],
            $data['berat_lahir'] ?? null,
            $data['panjang_lahir'] ?? null,
            $data['lingkar_kepala'] ?? null,
            $data['apgar_1_menit'] ?? null,
            $data['apgar_5_menit'] ?? null,
            $data['kondisi_lahir'] ?? null,
            $data['resusitasi'] ?? null,
            $data['jenis_resusitasi'] ?? null,
            $data['vitamin_k1'] ?? null,
            $data['salep_mata'] ?? null,
            $data['hb0_imunisasi'] ?? null,
            $data['waktu_hb0'] ?? null,
            $data['imd'] ?? null,
            $data['lama_imd'] ?? null,
            $data['kelainan_kongenital'] ?? null,
            $data['golongan_darah'] ?? null,
            $data['anak_ke'] ?? null,
            $data['catatan_kelahiran'] ?? null
        ]);

        return $pdo->lastInsertId();
    }

    // Memperbarui data bayi
    public static function update($pdo, $id, $data) {
        $sql = "UPDATE bayis SET
                    pasien_id = ?, nama_bayi = ?, tanggal_lahir = ?, jam_lahir = ?, jenis_kelamin = ?,
                    berat_lahir = ?, panjang_lahir = ?, lingkar_kepala = ?,
                    apgar_1_menit = ?, apgar_5_menit = ?, kondisi_lahir = ?,
                    resusitasi = ?, jenis_resusitasi = ?,
                    vitamin_k1 = ?, salep_mata = ?, hb0_imunisasi = ?, waktu_hb0 = ?,
                    imd = ?, lama_imd = ?, kelainan_kongenital = ?,
                    golongan_darah = ?, anak_ke = ?, catatan_kelahiran = ?
                WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['pasien_id'], $data['nama_bayi'], $data['tanggal_lahir'],
            $data['jam_lahir'] ?? null, $data['jenis_kelamin'],
            $data['berat_lahir'] ?? null, $data['panjang_lahir'] ?? null,
            $data['lingkar_kepala'] ?? null, $data['apgar_1_menit'] ?? null,
            $data['apgar_5_menit'] ?? null, $data['kondisi_lahir'] ?? null,
            $data['resusitasi'] ?? null, $data['jenis_resusitasi'] ?? null,
            $data['vitamin_k1'] ?? null, $data['salep_mata'] ?? null,
            $data['hb0_imunisasi'] ?? null, $data['waktu_hb0'] ?? null,
            $data['imd'] ?? null, $data['lama_imd'] ?? null,
            $data['kelainan_kongenital'] ?? null, $data['golongan_darah'] ?? null,
            $data['anak_ke'] ?? null, $data['catatan_kelahiran'] ?? null, $id
        ]);
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

    // Mengambil semua bayi berdasarkan ID pasien (ibu)
    public static function getAllByPasienId($pdo, $pasien_id) {
        $stmt = $pdo->prepare("
            SELECT * FROM bayis
            WHERE pasien_id = ?
            ORDER BY tanggal_lahir DESC
        ");
        $stmt->execute([$pasien_id]);
        return $stmt->fetchAll();
    }

    // Mengambil data pertumbuhan bayi
    public static function getGrowthDataById($pdo, $bayi_id) {
        $stmt = $pdo->prepare("
            SELECT
                tanggal_kunjungan,
                berat_badan,
                panjang_badan,
                lingkar_kepala
            FROM
                kunjungan_bayi
            WHERE
                bayi_id = ?
                AND (berat_badan IS NOT NULL OR panjang_badan IS NOT NULL OR lingkar_kepala IS NOT NULL)
            ORDER BY
                tanggal_kunjungan ASC
        ");
        $stmt->execute([$bayi_id]);
        return $stmt->fetchAll();
    }

    // Menghitung bayi berdasarkan kondisi lahir
    public static function countByKondisiLahir($pdo) {
        $stmt = $pdo->query("
            SELECT
                kondisi_lahir,
                COUNT(*) as jumlah
            FROM bayis
            WHERE kondisi_lahir IS NOT NULL
            GROUP BY kondisi_lahir
        ");
        return $stmt->fetchAll();
    }

    // Menghitung IMD yang berhasil
    public static function countIMD($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM bayis
            WHERE imd = 'Ya'
        ");
        return $stmt->fetchColumn();
    }

    // Menghitung pemberian Vitamin K1
    public static function countVitaminK1($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM bayis
            WHERE vitamin_k1 = 'Ya'
        ");
        return $stmt->fetchColumn();
    }

    // Menghitung pemberian Salep Mata
    public static function countSalepMata($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM bayis
            WHERE salep_mata = 'Ya'
        ");
        return $stmt->fetchColumn();
    }

    // Menghitung pemberian HB-0
    public static function countHB0($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM bayis
            WHERE hb0_imunisasi = 'Ya'
        ");
        return $stmt->fetchColumn();
    }

    // Menghitung bayi dengan resusitasi
    public static function countResusitasi($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM bayis
            WHERE resusitasi = 'Ya'
        ");
        return $stmt->fetchColumn();
    }

    // Menghitung bayi dengan kelainan kongenital
    public static function countKelainanKongenital($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM bayis
            WHERE kelainan_kongenital IS NOT NULL
            AND kelainan_kongenital != ''
            AND kelainan_kongenital != 'Tidak Ada'
        ");
        return $stmt->fetchColumn();
    }

    // Menghitung rata-rata APGAR score
    public static function getAverageAPGAR($pdo) {
        $stmt = $pdo->query("
            SELECT
                AVG(apgar_1_menit) as avg_apgar_1,
                AVG(apgar_5_menit) as avg_apgar_5
            FROM bayis
            WHERE apgar_1_menit IS NOT NULL AND apgar_5_menit IS NOT NULL
        ");
        $result = $stmt->fetch();
        return [
            'apgar_1_menit' => round($result['avg_apgar_1'] ?? 0, 1),
            'apgar_5_menit' => round($result['avg_apgar_5'] ?? 0, 1)
        ];
    }

    // Statistik neonatal esensial
    public static function countNeonatalEssentials($pdo) {
        $stmt = $pdo->query("
            SELECT
                COUNT(*) as total_bayi,
                SUM(CASE WHEN imd = 'Ya' THEN 1 ELSE 0 END) as imd_count,
                SUM(CASE WHEN vitamin_k1 = 'Ya' THEN 1 ELSE 0 END) as vitamin_k1_count,
                SUM(CASE WHEN salep_mata = 'Ya' THEN 1 ELSE 0 END) as salep_mata_count,
                SUM(CASE WHEN hb0_imunisasi = 'Ya' THEN 1 ELSE 0 END) as hb0_count,
                SUM(CASE WHEN resusitasi = 'Ya' THEN 1 ELSE 0 END) as resusitasi_count
            FROM bayis
        ");
        return $stmt->fetch();
    }

    // Menghitung bayi BBLR (Berat Badan Lahir Rendah)
    public static function countBBLR($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM bayis
            WHERE berat_lahir < 2500
        ");
        return $stmt->fetchColumn();
    }

    // Menghitung bayi dengan APGAR <7
    public static function countLowAPGAR($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM bayis
            WHERE apgar_5_menit < 7
        ");
        return $stmt->fetchColumn();
    }

    // Menghitung total bayi
    public static function countAll($pdo) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM bayis");
        return $stmt->fetchColumn();
    }

    // Menghitung bayi bulan ini
    public static function countThisMonth($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM bayis
            WHERE MONTH(tanggal_lahir) = MONTH(CURRENT_DATE())
            AND YEAR(tanggal_lahir) = YEAR(CURRENT_DATE())
        ");
        return $stmt->fetchColumn();
    }
}
?>
