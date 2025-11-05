<?php
// File: app/models/KunjunganBayi.php
// Model untuk mengelola data kunjungan bayi - Updated sesuai Standar KN1-KN3 Kemenkes RI

class KunjunganBayi {

    // Mengambil semua data kunjungan bayi dengan join ke tabel bayi dan pasien (ibu)
    public static function getAll($pdo, $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $stmt = $pdo->prepare("
            SELECT
                kb.*,
                b.nama_bayi,
                b.tanggal_lahir,
                p.nama_pasien as nama_ibu,
                p.no_rm
            FROM
                kunjungan_bayi kb
            JOIN
                bayis b ON kb.bayi_id = b.id
            JOIN
                pasiens p ON b.pasien_id = p.id
            ORDER BY
                kb.tanggal_kunjungan DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Menyimpan data kunjungan bayi baru dengan kolom tambahan (KN & Tumbuh Kembang)
    public static function create($pdo, $data) {
        $sql = "INSERT INTO kunjungan_bayi (
                    bayi_id, tanggal_kunjungan, kunjungan_ke,
                    usia_hari, usia_bulan,
                    berat_badan, panjang_badan, lingkar_kepala, suhu_tubuh,
                    asi_eksklusif, tanda_bahaya, perkembangan, status_gizi,
                    vitamin_a, skrining_tumbuh_kembang, stimulasi,
                    jenis_imunisasi, catatan_klinis, pemberi_layanan
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['bayi_id'],
            $data['tanggal_kunjungan'],
            $data['kunjungan_ke'] ?? null,
            $data['usia_hari'] ?? null,
            $data['usia_bulan'] ?? null,
            $data['berat_badan'] ?? null,
            $data['panjang_badan'] ?? null,
            $data['lingkar_kepala'] ?? null,
            $data['suhu_tubuh'] ?? null,
            $data['asi_eksklusif'] ?? null,
            $data['tanda_bahaya'] ?? null,
            $data['perkembangan'] ?? null,
            $data['status_gizi'] ?? null,
            $data['vitamin_a'] ?? null,
            $data['skrining_tumbuh_kembang'] ?? null,
            $data['stimulasi'] ?? null,
            $data['jenis_imunisasi'] ?? null,
            $data['catatan_klinis'] ?? null,
            $data['pemberi_layanan'] ?? null
        ]);

        return $pdo->lastInsertId();
    }

    // Update data kunjungan bayi
    public static function update($pdo, $id, $data) {
        $sql = "UPDATE kunjungan_bayi SET
                    bayi_id = ?, tanggal_kunjungan = ?, kunjungan_ke = ?,
                    usia_hari = ?, usia_bulan = ?,
                    berat_badan = ?, panjang_badan = ?, lingkar_kepala = ?, suhu_tubuh = ?,
                    asi_eksklusif = ?, tanda_bahaya = ?, perkembangan = ?, status_gizi = ?,
                    vitamin_a = ?, skrining_tumbuh_kembang = ?, stimulasi = ?,
                    jenis_imunisasi = ?, catatan_klinis = ?, pemberi_layanan = ?
                WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['bayi_id'], $data['tanggal_kunjungan'], $data['kunjungan_ke'] ?? null,
            $data['usia_hari'] ?? null, $data['usia_bulan'] ?? null,
            $data['berat_badan'] ?? null, $data['panjang_badan'] ?? null,
            $data['lingkar_kepala'] ?? null, $data['suhu_tubuh'] ?? null,
            $data['asi_eksklusif'] ?? null, $data['tanda_bahaya'] ?? null,
            $data['perkembangan'] ?? null, $data['status_gizi'] ?? null,
            $data['vitamin_a'] ?? null, $data['skrining_tumbuh_kembang'] ?? null,
            $data['stimulasi'] ?? null, $data['jenis_imunisasi'] ?? null,
            $data['catatan_klinis'] ?? null, $data['pemberi_layanan'] ?? null, $id
        ]);
    }

    // Mengambil data kunjungan bayi berdasarkan ID
    public static function findById($pdo, $id) {
        $stmt = $pdo->prepare("
            SELECT
                kb.*,
                b.nama_bayi,
                b.tanggal_lahir,
                p.nama_pasien as nama_ibu,
                p.no_rm
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
        $stmt = $pdo->prepare("
            SELECT * FROM kunjungan_bayi
            WHERE bayi_id = ?
            ORDER BY tanggal_kunjungan DESC
        ");
        $stmt->execute([$bayi_id]);
        return $stmt->fetchAll();
    }

    // Menghitung total kunjungan bayi
    public static function countAll($pdo) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM kunjungan_bayi");
        return $stmt->fetchColumn();
    }

    // Menghitung total kunjungan bayi bulan ini
    public static function countThisMonth($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM kunjungan_bayi
            WHERE MONTH(tanggal_kunjungan) = MONTH(CURRENT_DATE())
            AND YEAR(tanggal_kunjungan) = YEAR(CURRENT_DATE())
        ");
        return $stmt->fetchColumn();
    }

    // Menghitung kunjungan berdasarkan KN
    public static function countByKunjunganKe($pdo) {
        $stmt = $pdo->query("
            SELECT
                kunjungan_ke,
                COUNT(*) as jumlah
            FROM kunjungan_bayi
            WHERE kunjungan_ke IS NOT NULL
            GROUP BY kunjungan_ke
        ");
        return $stmt->fetchAll();
    }

    // Menghitung ASI eksklusif
    public static function countASIEksklusif($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM kunjungan_bayi
            WHERE asi_eksklusif = 'Ya'
        ");
        return $stmt->fetchColumn();
    }

    // Menghitung pemberian Vitamin A
    public static function countVitaminA($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM kunjungan_bayi
            WHERE vitamin_a = 'Sudah'
        ");
        return $stmt->fetchColumn();
    }

    // Menghitung tanda bahaya
    public static function countTandaBahaya($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM kunjungan_bayi
            WHERE tanda_bahaya IS NOT NULL
            AND tanda_bahaya != ''
            AND tanda_bahaya != 'Tidak Ada'
        ");
        return $stmt->fetchColumn();
    }

    // Menghitung skrining tumbuh kembang
    public static function countSkriningTumbuhKembang($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM kunjungan_bayi
            WHERE skrining_tumbuh_kembang IS NOT NULL
            AND skrining_tumbuh_kembang != ''
        ");
        return $stmt->fetchColumn();
    }

    // Menghitung status gizi
    public static function countByStatusGizi($pdo) {
        $stmt = $pdo->query("
            SELECT
                status_gizi,
                COUNT(*) as jumlah
            FROM kunjungan_bayi
            WHERE status_gizi IS NOT NULL
            GROUP BY status_gizi
        ");
        return $stmt->fetchAll();
    }

    // Monitoring pertumbuhan bayi
    public static function getGrowthMonitoring($pdo, $bayi_id) {
        $stmt = $pdo->prepare("
            SELECT
                tanggal_kunjungan,
                usia_bulan,
                berat_badan,
                panjang_badan,
                lingkar_kepala,
                status_gizi
            FROM kunjungan_bayi
            WHERE bayi_id = ?
            AND (berat_badan IS NOT NULL OR panjang_badan IS NOT NULL OR lingkar_kepala IS NOT NULL)
            ORDER BY tanggal_kunjungan ASC
        ");
        $stmt->execute([$bayi_id]);
        return $stmt->fetchAll();
    }

    // Statistik kunjungan bayi
    public static function getBayiVisitStatistics($pdo) {
        $stmt = $pdo->query("
            SELECT
                COUNT(*) as total_kunjungan,
                SUM(CASE WHEN kunjungan_ke = 'KN1' THEN 1 ELSE 0 END) as kn1_count,
                SUM(CASE WHEN kunjungan_ke = 'KN2' THEN 1 ELSE 0 END) as kn2_count,
                SUM(CASE WHEN kunjungan_ke = 'KN3' THEN 1 ELSE 0 END) as kn3_count,
                SUM(CASE WHEN asi_eksklusif = 'Ya' THEN 1 ELSE 0 END) as asi_eksklusif_count,
                SUM(CASE WHEN vitamin_a = 'Sudah' THEN 1 ELSE 0 END) as vitamin_a_count,
                SUM(CASE WHEN tanda_bahaya IS NOT NULL AND tanda_bahaya != 'Tidak Ada' THEN 1 ELSE 0 END) as tanda_bahaya_count,
                SUM(CASE WHEN skrining_tumbuh_kembang IS NOT NULL THEN 1 ELSE 0 END) as skrining_count
            FROM kunjungan_bayi
        ");
        return $stmt->fetch();
    }

    // Hapus data kunjungan bayi
    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM kunjungan_bayi WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
