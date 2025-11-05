<?php
// File: app/models/Laporan.php (Final)
// Model untuk mengelola semua query yang berhubungan dengan laporan.

class Laporan {
    
    // Mengambil data semua jenis kunjungan dalam rentang tanggal tertentu
    public static function getKunjunganByDateRange($pdo, $tanggal_awal, $tanggal_akhir) {
        $sql = "
            (SELECT ka.tanggal_kunjungan as tanggal, p.no_rm, p.nama_pasien, 'ANC' as jenis_layanan, ka.diagnosis as keterangan FROM kunjungan_anc ka JOIN pasiens p ON ka.pasien_id = p.id WHERE ka.tanggal_kunjungan BETWEEN ? AND ?)
            UNION ALL
            (SELECT ki.tanggal_kunjungan as tanggal, p.no_rm, p.nama_pasien, 'INC' as jenis_layanan, ki.diagnosis as keterangan FROM kunjungan_inc ki JOIN pasiens p ON ki.pasien_id = p.id WHERE ki.tanggal_kunjungan BETWEEN ? AND ?)
            UNION ALL
            (SELECT kn.tanggal_kunjungan as tanggal, p.no_rm, p.nama_pasien, 'Nifas' as jenis_layanan, kn.keluhan as keterangan FROM kunjungan_nifas kn JOIN pasiens p ON kn.pasien_id = p.id WHERE kn.tanggal_kunjungan BETWEEN ? AND ?)
            UNION ALL
            (SELECT lk.tanggal_layanan as tanggal, p.no_rm, p.nama_pasien, 'KB' as jenis_layanan, CONCAT(lk.jenis_layanan, ' - ', lk.metode_kb) as keterangan FROM layanan_kb lk JOIN pasiens p ON lk.pasien_id = p.id WHERE lk.tanggal_layanan BETWEEN ? AND ?)
            UNION ALL
            (SELECT i.tanggal_imunisasi as tanggal, p.no_rm, b.nama_bayi as nama_pasien, 'Imunisasi' as jenis_layanan, i.jenis_imunisasi as keterangan FROM imunisasi i JOIN bayis b ON i.bayi_id = b.id JOIN pasiens p ON b.pasien_id = p.id WHERE i.tanggal_imunisasi BETWEEN ? AND ?)
            UNION ALL
            (SELECT kb.tanggal_kunjungan as tanggal, p.no_rm, b.nama_bayi as nama_pasien, 'Kunjungan Bayi' as jenis_layanan, kb.catatan_klinis as keterangan FROM kunjungan_bayi kb JOIN bayis b ON kb.bayi_id = b.id JOIN pasiens p ON b.pasien_id = p.id WHERE kb.tanggal_kunjungan BETWEEN ? AND ?)
            ORDER BY tanggal DESC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$tanggal_awal, $tanggal_akhir, $tanggal_awal, $tanggal_akhir, $tanggal_awal, $tanggal_akhir, $tanggal_awal, $tanggal_akhir, $tanggal_awal, $tanggal_akhir, $tanggal_awal, $tanggal_akhir]);
        return $stmt->fetchAll();
    }

    // Mengambil jadwal kunjungan mendatang dari ANC dan KB
    public static function getJadwalMendatang($pdo, $days_limit = 30) {
        $sql = "
            (SELECT ka.jadwal_kunjungan_ulang as tanggal_jadwal, p.nama_pasien, 'Kunjungan Ulang ANC' as jenis_kegiatan, p.no_telepon, p.id as pasien_id FROM kunjungan_anc ka JOIN pasiens p ON ka.pasien_id = p.id WHERE ka.jadwal_kunjungan_ulang >= CURDATE() AND ka.jadwal_kunjungan_ulang <= DATE_ADD(CURDATE(), INTERVAL :limit_days DAY))
            UNION ALL
            (SELECT lk.jadwal_kembali as tanggal_jadwal, p.nama_pasien, CONCAT('Kontrol KB (', lk.metode_kb, ')') as jenis_kegiatan, p.no_telepon, p.id as pasien_id FROM layanan_kb lk JOIN pasiens p ON lk.pasien_id = p.id WHERE lk.jadwal_kembali >= CURDATE() AND lk.jadwal_kembali <= DATE_ADD(CURDATE(), INTERVAL :limit_days DAY))
            ORDER BY tanggal_jadwal ASC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':limit_days', $days_limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Menarik data untuk Laporan Kohort Bayi.
     * Mengambil data kelahiran dan status bayi per bulan dalam satu tahun.
     */
    public static function getLaporanKohort($pdo, $tahun) {
        $data = [];

        // Inisialisasi data untuk 12 bulan
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $data['bulan_' . $bulan] = [
                'lahir' => 0,
                'hidup_12_bulan' => 0
            ];
        }

        // Query untuk menghitung bayi lahir per bulan
        $sql_lahir = "
            SELECT
                MONTH(tanggal_lahir) as bulan,
                COUNT(*) as jumlah_lahir
            FROM bayis
            WHERE YEAR(tanggal_lahir) = :tahun
            GROUP BY MONTH(tanggal_lahir)
        ";

        $stmt_lahir = $pdo->prepare($sql_lahir);
        $stmt_lahir->execute(['tahun' => $tahun]);
        $result_lahir = $stmt_lahir->fetchAll();

        // Isi data lahir
        foreach ($result_lahir as $row) {
            $data['bulan_' . $row['bulan']]['lahir'] = $row['jumlah_lahir'];
        }

        // Query untuk menghitung bayi yang hidup sampai 12 bulan
        // Asumsi: bayi yang masih hidup jika tidak ada catatan kematian dan usia < 12 bulan
        // Dalam implementasi nyata, perlu tabel khusus untuk tracking kematian bayi
        $sql_hidup = "
            SELECT
                MONTH(tanggal_lahir) as bulan,
                COUNT(*) as jumlah_hidup
            FROM bayis
            WHERE YEAR(tanggal_lahir) = :tahun
                AND (catatan_kelahiran NOT LIKE '%meninggal%' OR catatan_kelahiran IS NULL)
                AND TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) >= 12
            GROUP BY MONTH(tanggal_lahir)
        ";

        $stmt_hidup = $pdo->prepare($sql_hidup);
        $stmt_hidup->execute(['tahun' => $tahun]);
        $result_hidup = $stmt_hidup->fetchAll();

        // Isi data hidup 12 bulan
        foreach ($result_hidup as $row) {
            $data['bulan_' . $row['bulan']]['hidup_12_bulan'] = $row['jumlah_hidup'];
        }

        return $data;
    }

    // Mengambil statistik kunjungan untuk dashboard
    public static function getVisitStatistics($pdo) {
        $sql = "
            SELECT 
                DATE(tanggal) as date,
                jenis_layanan,
                COUNT(*) as count
            FROM (
                (SELECT ka.tanggal_kunjungan as tanggal, 'ANC' as jenis_layanan FROM kunjungan_anc ka WHERE ka.tanggal_kunjungan >= DATE_SUB(CURDATE(), INTERVAL 30 DAY))
                UNION ALL
                (SELECT ki.tanggal_kunjungan as tanggal, 'INC' as jenis_layanan FROM kunjungan_inc ki WHERE ki.tanggal_kunjungan >= DATE_SUB(CURDATE(), INTERVAL 30 DAY))
                UNION ALL
                (SELECT kn.tanggal_kunjungan as tanggal, 'Nifas' as jenis_layanan FROM kunjungan_nifas kn WHERE kn.tanggal_kunjungan >= DATE_SUB(CURDATE(), INTERVAL 30 DAY))
                UNION ALL
                (SELECT lk.tanggal_layanan as tanggal, 'KB' as jenis_layanan FROM layanan_kb lk WHERE lk.tanggal_layanan >= DATE_SUB(CURDATE(), INTERVAL 30 DAY))
                UNION ALL
                (SELECT i.tanggal_imunisasi as tanggal, 'Imunisasi' as jenis_layanan FROM imunisasi i WHERE i.tanggal_imunisasi >= DATE_SUB(CURDATE(), INTERVAL 30 DAY))
                UNION ALL
                (SELECT kb.tanggal_kunjungan as tanggal, 'Kunjungan Bayi' as jenis_layanan FROM kunjungan_bayi kb WHERE kb.tanggal_kunjungan >= DATE_SUB(CURDATE(), INTERVAL 30 DAY))
            ) as all_visits
            GROUP BY DATE(tanggal), jenis_layanan
            ORDER BY date DESC, jenis_layanan
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Mengambil ringkasan kunjungan harian untuk 7 hari terakhir
    public static function getDailyVisitSummary($pdo, $days = 7) {
        $sql = "
            SELECT 
                DATE(tanggal) as date,
                COUNT(*) as total_visits,
                GROUP_CONCAT(DISTINCT jenis_layanan ORDER BY jenis_layanan SEPARATOR ', ') as services
            FROM (
                (SELECT ka.tanggal_kunjungan as tanggal, 'ANC' as jenis_layanan FROM kunjungan_anc ka WHERE ka.tanggal_kunjungan >= DATE_SUB(CURDATE(), INTERVAL :days DAY))
                UNION ALL
                (SELECT ki.tanggal_kunjungan as tanggal, 'INC' as jenis_layanan FROM kunjungan_inc ki WHERE ki.tanggal_kunjungan >= DATE_SUB(CURDATE(), INTERVAL :days DAY))
                UNION ALL
                (SELECT kn.tanggal_kunjungan as tanggal, 'Nifas' as jenis_layanan FROM kunjungan_nifas kn WHERE kn.tanggal_kunjungan >= DATE_SUB(CURDATE(), INTERVAL :days DAY))
                UNION ALL
                (SELECT lk.tanggal_layanan as tanggal, 'KB' as jenis_layanan FROM layanan_kb lk WHERE lk.tanggal_layanan >= DATE_SUB(CURDATE(), INTERVAL :days DAY))
                UNION ALL
                (SELECT i.tanggal_imunisasi as tanggal, 'Imunisasi' as jenis_layanan FROM imunisasi i WHERE i.tanggal_imunisasi >= DATE_SUB(CURDATE(), INTERVAL :days DAY))
                UNION ALL
                (SELECT kb.tanggal_kunjungan as tanggal, 'Kunjungan Bayi' as jenis_layanan FROM kunjungan_bayi kb WHERE kb.tanggal_kunjungan >= DATE_SUB(CURDATE(), INTERVAL :days DAY))
            ) as all_visits
            GROUP BY DATE(tanggal)
            ORDER BY date DESC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Mengambil total kunjungan per jenis layanan bulan ini
    public static function getMonthlyServiceStats($pdo) {
        $sql = "
            SELECT
                jenis_layanan,
                COUNT(*) as total,
                COUNT(CASE WHEN DATE(tanggal) = CURDATE() THEN 1 END) as today
            FROM (
                (SELECT ka.tanggal_kunjungan as tanggal, 'ANC' as jenis_layanan FROM kunjungan_anc ka WHERE MONTH(ka.tanggal_kunjungan) = MONTH(CURDATE()) AND YEAR(ka.tanggal_kunjungan) = YEAR(CURDATE()))
                UNION ALL
                (SELECT ki.tanggal_kunjungan as tanggal, 'INC' as jenis_layanan FROM kunjungan_inc ki WHERE MONTH(ki.tanggal_kunjungan) = MONTH(CURDATE()) AND YEAR(ki.tanggal_kunjungan) = YEAR(CURDATE()))
                UNION ALL
                (SELECT kn.tanggal_kunjungan as tanggal, 'Nifas' as jenis_layanan FROM kunjungan_nifas kn WHERE MONTH(kn.tanggal_kunjungan) = MONTH(CURDATE()) AND YEAR(kn.tanggal_kunjungan) = YEAR(CURDATE()))
                UNION ALL
                (SELECT lk.tanggal_layanan as tanggal, 'KB' as jenis_layanan FROM layanan_kb lk WHERE MONTH(lk.tanggal_layanan) = MONTH(CURDATE()) AND YEAR(lk.tanggal_layanan) = YEAR(CURDATE()))
                UNION ALL
                (SELECT i.tanggal_imunisasi as tanggal, 'Imunisasi' as jenis_layanan FROM imunisasi i WHERE MONTH(i.tanggal_imunisasi) = MONTH(CURDATE()) AND YEAR(i.tanggal_imunisasi) = YEAR(CURDATE()))
                UNION ALL
                (SELECT kb.tanggal_kunjungan as tanggal, 'Kunjungan Bayi' as jenis_layanan FROM kunjungan_bayi kb WHERE MONTH(kb.tanggal_kunjungan) = MONTH(CURDATE()) AND YEAR(kb.tanggal_kunjungan) = YEAR(CURDATE()))
            ) as all_visits
            GROUP BY jenis_layanan
            ORDER BY total DESC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Mengambil data untuk Laporan PWS KIA (Pemantauan Wilayah Setempat Kesehatan Ibu dan Anak)
    public static function getLaporanBulanan($pdo, $tahun, $bulan) {
        $data = [];

        // ANC data
        $data['anc'] = self::getAncData($pdo, $tahun, $bulan);

        // Persalinan data
        $data['persalinan'] = self::getPersalinanData($pdo, $tahun, $bulan);

        // Nifas data
        $data['nifas'] = self::getNifasData($pdo, $tahun, $bulan);

        // Bayi data
        $data['bayi'] = self::getBayiData($pdo, $tahun, $bulan);

        // KB data
        $data['kb'] = self::getKbData($pdo, $tahun, $bulan);

        // Imunisasi data
        $data['imunisasi'] = self::getImunisasiData($pdo, $tahun, $bulan);

        // Kunjungan Bayi data
        $data['kunjungan_bayi'] = self::getKunjunganBayiData($pdo, $tahun, $bulan);

        return $data;
    }

    private static function getAncData($pdo, $tahun, $bulan) {
        $sql = "
            SELECT
                COUNT(*) as total_kunjungan
            FROM kunjungan_anc
            WHERE YEAR(tanggal_kunjungan) = :tahun AND MONTH(tanggal_kunjungan) = :bulan
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['tahun' => $tahun, 'bulan' => $bulan]);
        $result = $stmt->fetch();

        // Set default values for indicators not available in current database
        $result['kunjungan_k1'] = 0;
        $result['kunjungan_k4'] = 0;
        $result['tablet_fe'] = 0;
        $result['imunisasi_tt'] = 0;
        $result['protein_urin'] = 0;
        $result['risiko_tinggi'] = 0;

        return $result;
    }

    private static function getPersalinanData($pdo, $tahun, $bulan) {
        $sql = "
            SELECT
                COUNT(*) as total_persalinan
            FROM bayis
            WHERE YEAR(tanggal_lahir) = :tahun AND MONTH(tanggal_lahir) = :bulan
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['tahun' => $tahun, 'bulan' => $bulan]);
        $result = $stmt->fetch();

        // Set default values for indicators not available in current database
        $result['normal'] = 0;
        $result['imd_count'] = 0;
        $result['pph_count'] = 0;
        $result['robekan_berat'] = 0;
        $result['asfiksia_berat'] = 0;

        return $result;
    }

    private static function getNifasData($pdo, $tahun, $bulan) {
        $sql = "
            SELECT
                COUNT(*) as total_kunjungan
            FROM kunjungan_nifas
            WHERE YEAR(tanggal_kunjungan) = :tahun AND MONTH(tanggal_kunjungan) = :bulan
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['tahun' => $tahun, 'bulan' => $bulan]);
        $result = $stmt->fetch();

        // Set default values for indicators not available in current database
        $result['kf1_count'] = 0;
        $result['vitamin_a_count'] = 0;
        $result['tablet_fe_count'] = 0;
        $result['asi_lancar_count'] = 0;
        $result['subinvolusi_count'] = 0;
        $result['tanda_bahaya_count'] = 0;

        return $result;
    }

    private static function getBayiData($pdo, $tahun, $bulan) {
        $sql = "
            SELECT
                COUNT(*) as total_lahir,
                COUNT(CASE WHEN berat_lahir < 2500 THEN 1 END) as bblr
            FROM bayis
            WHERE YEAR(tanggal_lahir) = :tahun AND MONTH(tanggal_lahir) = :bulan
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['tahun' => $tahun, 'bulan' => $bulan]);
        $result = $stmt->fetch();

        // Set default values for indicators not available in current database
        $result['imd_count'] = 0;
        $result['vitamin_k1_count'] = 0;
        $result['salep_mata_count'] = 0;
        $result['hb0_count'] = 0;
        $result['apgar_rendah_count'] = 0;

        return $result;
    }

    private static function getKbData($pdo, $tahun, $bulan) {
        $sql = "
            SELECT
                COUNT(*) as total_layanan
            FROM layanan_kb
            WHERE YEAR(tanggal_layanan) = :tahun AND MONTH(tanggal_layanan) = :bulan
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['tahun' => $tahun, 'bulan' => $bulan]);
        $result = $stmt->fetch();

        // Set default values for indicators not available in current database
        $result['akseptor_baru'] = 0;
        $result['aktif'] = 0;
        $result['informed_consent'] = 0;
        $result['efek_samping_count'] = 0;

        return $result;
    }

    private static function getImunisasiData($pdo, $tahun, $bulan) {
        $sql = "
            SELECT
                COUNT(*) as total_imunisasi
            FROM imunisasi
            WHERE YEAR(tanggal_imunisasi) = :tahun AND MONTH(tanggal_imunisasi) = :bulan
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['tahun' => $tahun, 'bulan' => $bulan]);
        $result = $stmt->fetch();

        // Set default values for indicators not available in current database
        $result['hb0'] = 0;
        $result['bcg'] = 0;
        $result['dpt'] = 0;
        $result['polio'] = 0;
        $result['campak'] = 0;
        $result['kipi_count'] = 0;

        return $result;
    }

    private static function getKunjunganBayiData($pdo, $tahun, $bulan) {
        $sql = "
            SELECT
                COUNT(*) as total_kunjungan
            FROM kunjungan_bayi
            WHERE YEAR(tanggal_kunjungan) = :tahun AND MONTH(tanggal_kunjungan) = :bulan
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['tahun' => $tahun, 'bulan' => $bulan]);
        $result = $stmt->fetch();

        // Set default values for indicators not available in current database
        $result['kn1_count'] = 0;
        $result['vitamin_a_count'] = 0;
        $result['skrining_count'] = 0;
        $result['status_gizi_baik'] = 0;
        $result['asi_eksklusif_count'] = 0;
        $result['tanda_bahaya_count'] = 0;

        return $result;
    }
}
?>
