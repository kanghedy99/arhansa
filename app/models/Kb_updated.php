<?php
// File: app/models/Kb.php
// Model untuk mengelola data layanan Keluarga Berencana (KB) - Updated sesuai Standar Kemenkes RI

class Kb {

    // Mengambil semua data layanan KB dengan join ke tabel pasien
    public static function getAll($pdo, $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $stmt = $pdo->prepare("
            SELECT
                lk.*,
                p.nama_pasien,
                p.no_rm,
                p.gravida_paritas
            FROM
                layanan_kb lk
            JOIN
                pasiens p ON lk.pasien_id = p.id
            ORDER BY
                lk.tanggal_layanan DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Menghitung total layanan KB
    public static function countAll($pdo) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM layanan_kb");
        return $stmt->fetchColumn();
    }

    // Menyimpan data layanan KB baru dengan kolom tambahan (Skrining & Consent)
    public static function create($pdo, $data) {
        $sql = "INSERT INTO layanan_kb (
                    pasien_id, tanggal_layanan, metode_kb, jenis_layanan,
                    tekanan_darah, berat_badan, tinggi_badan,
                    riwayat_kesehatan, efek_samping, konseling,
                    informed_consent, biaya, status_kb,
                    keluhan, tindakan, jadwal_kembali
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['pasien_id'],
            $data['tanggal_layanan'],
            $data['metode_kb'],
            $data['jenis_layanan'],
            $data['tekanan_darah'] ?? null,
            $data['berat_badan'] ?? null,
            $data['tinggi_badan'] ?? null,
            $data['riwayat_kesehatan'] ?? null,
            $data['efek_samping'] ?? null,
            $data['konseling'] ?? null,
            $data['informed_consent'] ?? null,
            $data['biaya'] ?? null,
            $data['status_kb'] ?? null,
            $data['keluhan'] ?? null,
            $data['tindakan'] ?? null,
            $data['jadwal_kembali'] ?? null
        ]);

        return $pdo->lastInsertId();
    }

    // Update data layanan KB
    public static function update($pdo, $id, $data) {
        $sql = "UPDATE layanan_kb SET
                    pasien_id = ?, tanggal_layanan = ?, metode_kb = ?, jenis_layanan = ?,
                    tekanan_darah = ?, berat_badan = ?, tinggi_badan = ?,
                    riwayat_kesehatan = ?, efek_samping = ?, konseling = ?,
                    informed_consent = ?, biaya = ?, status_kb = ?,
                    keluhan = ?, tindakan = ?, jadwal_kembali = ?
                WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['pasien_id'], $data['tanggal_layanan'], $data['metode_kb'], $data['jenis_layanan'],
            $data['tekanan_darah'] ?? null, $data['berat_badan'] ?? null, $data['tinggi_badan'] ?? null,
            $data['riwayat_kesehatan'] ?? null, $data['efek_samping'] ?? null, $data['konseling'] ?? null,
            $data['informed_consent'] ?? null, $data['biaya'] ?? null, $data['status_kb'] ?? null,
            $data['keluhan'] ?? null, $data['tindakan'] ?? null, $data['jadwal_kembali'] ?? null, $id
        ]);
    }

    // Mengambil data KB berdasarkan ID
    public static function findById($pdo, $id) {
        $stmt = $pdo->prepare("
            SELECT lk.*, p.nama_pasien, p.no_rm
            FROM layanan_kb lk
            JOIN pasiens p ON lk.pasien_id = p.id
            WHERE lk.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Mengambil semua riwayat KB berdasarkan ID Pasien
    public static function findByPasienId($pdo, $pasien_id) {
        $stmt = $pdo->prepare("
            SELECT * FROM layanan_kb
            WHERE pasien_id = ?
            ORDER BY tanggal_layanan DESC
        ");
        $stmt->execute([$pasien_id]);
        return $stmt->fetchAll();
    }

    // Menghitung total layanan KB bulan ini
    public static function countThisMonth($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM layanan_kb
            WHERE MONTH(tanggal_layanan) = MONTH(CURRENT_DATE())
            AND YEAR(tanggal_layanan) = YEAR(CURRENT_DATE())
        ");
        return $stmt->fetchColumn();
    }

    // Menghitung akseptor aktif
    public static function countAkseptorAktif($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(DISTINCT pasien_id) FROM layanan_kb
            WHERE status_kb = 'Aktif'
        ");
        return $stmt->fetchColumn();
    }

    // Menghitung berdasarkan metode KB
    public static function countByMetodeKB($pdo) {
        $stmt = $pdo->query("
            SELECT
                metode_kb,
                COUNT(*) as jumlah
            FROM layanan_kb
            WHERE metode_kb IS NOT NULL
            GROUP BY metode_kb
            ORDER BY jumlah DESC
        ");
        return $stmt->fetchAll();
    }

    // Menghitung berdasarkan jenis layanan
    public static function countByJenisLayanan($pdo) {
        $stmt = $pdo->query("
            SELECT
                jenis_layanan,
                COUNT(*) as jumlah
            FROM layanan_kb
            WHERE jenis_layanan IS NOT NULL
            GROUP BY jenis_layanan
        ");
        return $stmt->fetchAll();
    }

    // Menghitung dropout rate
    public static function getDropoutRate($pdo) {
        $stmt = $pdo->query("
            SELECT
                COUNT(*) as total,
                SUM(CASE WHEN status_kb = 'Berhenti' THEN 1 ELSE 0 END) as berhenti_count
            FROM layanan_kb
        ");
        $result = $stmt->fetch();
        $total = $result['total'] ?? 0;
        $berhenti = $result['berhenti_count'] ?? 0;
        return $total > 0 ? round(($berhenti / $total) * 100, 1) : 0;
    }

    // Menghitung informed consent rate
    public static function getInformedConsentRate($pdo) {
        $stmt = $pdo->query("
            SELECT
                COUNT(*) as total,
                SUM(CASE WHEN informed_consent = 'Ya' THEN 1 ELSE 0 END) as consent_count
            FROM layanan_kb
        ");
        $result = $stmt->fetch();
        $total = $result['total'] ?? 0;
        $consent = $result['consent_count'] ?? 0;
        return $total > 0 ? round(($consent / $total) * 100, 1) : 0;
    }

    // Menghitung efek samping yang dilaporkan
    public static function countEfekSamping($pdo) {
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM layanan_kb
            WHERE efek_samping IS NOT NULL AND efek_samping != ''
        ");
        return $stmt->fetchColumn();
    }

    // Statistik KB
    public static function getKBStatistics($pdo) {
        $stmt = $pdo->query("
            SELECT
                COUNT(*) as total_layanan,
                SUM(CASE WHEN jenis_layanan = 'Baru' THEN 1 ELSE 0 END) as akseptor_baru,
                SUM(CASE WHEN jenis_layanan = 'Kunjungan Ulang' THEN 1 ELSE 0 END) as kunjungan_ulang,
                SUM(CASE WHEN jenis_layanan = 'Ganti Metode' THEN 1 ELSE 0 END) as ganti_metode,
                SUM(CASE WHEN status_kb = 'Aktif' THEN 1 ELSE 0 END) as aktif,
                SUM(CASE WHEN status_kb = 'Berhenti' THEN 1 ELSE 0 END) as berhenti,
                SUM(CASE WHEN informed_consent = 'Ya' THEN 1 ELSE 0 END) as informed_consent,
                SUM(CASE WHEN efek_samping IS NOT NULL AND efek_samping != '' THEN 1 ELSE 0 END) as efek_samping_count
            FROM layanan_kb
        ");
        return $stmt->fetch();
    }

    // Hapus data KB
    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM layanan_kb WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
