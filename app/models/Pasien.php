<?php
// File: app/models/Pasien.php (Final)
// Model untuk mengelola data pasien.

class Pasien {
    
    // Mengambil semua data pasien dengan paginasi
    public static function getAll($pdo, $page = 1, $limit = 1000) { // Increased limit for dropdowns
        $offset = ($page - 1) * $limit;
        $stmt = $pdo->prepare("SELECT * FROM pasiens ORDER BY nama_pasien ASC LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Menghitung total jumlah pasien
    public static function countAll($pdo) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM pasiens");
        return $stmt->fetchColumn();
    }

    // Mencari pasien berdasarkan ID
    public static function findById($pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM pasiens WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Menyimpan data pasien baru (dengan kolom tambahan)
    public static function create($pdo, $data) {
        $sql = "INSERT INTO pasiens (no_rm, nik, nama_pasien, tempat_lahir, tanggal_lahir, alamat_lengkap, no_telepon, pekerjaan, pendidikan_terakhir, golongan_darah, nama_suami, agama, status_pernikahan, pekerjaan_suami, hpht, gravida_paritas, riwayat_penyakit, hipertensi, diabetes, alergi_obat)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['no_rm'], $data['nik'], $data['nama_pasien'], $data['tempat_lahir'],
            $data['tanggal_lahir'], $data['alamat_lengkap'], $data['no_telepon'],
            $data['pekerjaan'], $data['pendidikan_terakhir'], $data['golongan_darah'],
            $data['nama_suami'], $data['agama'] ?: null, $data['status_pernikahan'] ?: null,
            $data['pekerjaan_suami'], $data['hpht'] ?: null, $data['gravida_paritas'] ?: null,
            $data['riwayat_penyakit'] ?: null, $data['hipertensi'] ?: null,
            $data['diabetes'] ?: null, $data['alergi_obat'] ?: null
        ]);
        return $pdo->lastInsertId();
    }

    // Memperbarui data pasien (dengan kolom tambahan)
    public static function update($pdo, $id, $data) {
        $sql = "UPDATE pasiens SET
                    no_rm = ?, nik = ?, nama_pasien = ?, tempat_lahir = ?, tanggal_lahir = ?,
                    alamat_lengkap = ?, no_telepon = ?, pekerjaan = ?, pendidikan_terakhir = ?,
                    golongan_darah = ?, nama_suami = ?, agama = ?, status_pernikahan = ?,
                    pekerjaan_suami = ?, hpht = ?, gravida_paritas = ?, riwayat_penyakit = ?,
                    hipertensi = ?, diabetes = ?, alergi_obat = ?
                WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['no_rm'], $data['nik'], $data['nama_pasien'], $data['tempat_lahir'],
            $data['tanggal_lahir'], $data['alamat_lengkap'], $data['no_telepon'],
            $data['pekerjaan'], $data['pendidikan_terakhir'], $data['golongan_darah'],
            $data['nama_suami'], $data['agama'] ?: null, $data['status_pernikahan'] ?: null,
            $data['pekerjaan_suami'], $data['hpht'] ?: null, $data['gravida_paritas'] ?: null,
            $data['riwayat_penyakit'] ?: null, $data['hipertensi'] ?: null,
            $data['diabetes'] ?: null, $data['alergi_obat'] ?: null, $id
        ]);
        return $stmt->rowCount();
    }


    // Menghapus data pasien
    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM pasiens WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

    // Memeriksa apakah No. RM sudah ada
    public static function isNoRmExists($pdo, $no_rm, $exclude_id = null) {
        $sql = "SELECT COUNT(*) FROM pasiens WHERE no_rm = ?";
        $params = [$no_rm];
        if ($exclude_id) {
            $sql .= " AND id != ?";
            $params[] = $exclude_id;
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Membuat Nomor Rekam Medis baru secara otomatis.
     * Format: Serial murni 7 digit (Contoh: 0000001, 0000002, dst.)
     */
    public static function generateNewNoRm($pdo) {
        // 1. Cari No. RM tertinggi yang hanya berisi angka (format serial baru)
        $sql = "SELECT MAX(CAST(no_rm AS UNSIGNED)) as max_no_rm FROM pasiens WHERE no_rm REGEXP '^[0-9]+$'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();

        // 2. Tentukan nomor urut berikutnya
        if ($result && $result['max_no_rm']) {
            $next_sequence = $result['max_no_rm'] + 1;
        } else {
            // Jika belum ada pasien dengan format serial, mulai dari 1
            $next_sequence = 1;
        }

        // 3. Format nomor urut menjadi 7 digit dengan leading zero
        return str_pad($next_sequence, 7, '0', STR_PAD_LEFT);
    }
    
    /**
     * FUNGSI BARU: Menganalisis dan menentukan status klinis terkini pasien.
     */
    public static function getClinicalStatus($pdo, $pasien_id) {
        // 1. Cek status kehamilan dari HPHT
        $stmt = $pdo->prepare("SELECT hpht FROM pasiens WHERE id = ?");
        $stmt->execute([$pasien_id]);
        $pasien = $stmt->fetch();

        if ($pasien && $pasien['hpht']) {
            $hphtDate = new DateTime($pasien['hpht']);
            $hplDate = (new DateTime($pasien['hpht']))->modify('+40 weeks');
            $now = new DateTime();

            // Jika HPL belum lewat, pasien dianggap hamil
            if ($now <= $hplDate) {
                return ['status' => 'Hamil', 'detail' => calculateGestationalAgeString($pasien['hpht'])];
            }
        }

        // 2. Jika tidak hamil, cek status Nifas dalam 42 hari terakhir
        $stmt = $pdo->prepare("SELECT tanggal_kunjungan FROM kunjungan_nifas WHERE pasien_id = ? ORDER BY tanggal_kunjungan DESC LIMIT 1");
        $stmt->execute([$pasien_id]);
        $lastNifas = $stmt->fetch();

        if ($lastNifas) {
            $nifasDate = new DateTime($lastNifas['tanggal_kunjungan']);
            $now = new DateTime();
            $diffDays = $now->diff($nifasDate)->days;
            if ($diffDays <= 42) {
                return ['status' => 'Masa Nifas', 'detail' => "Hari ke-{$diffDays}"];
            }
        }
        
        // 3. Jika tidak nifas, cek status KB aktif
        $stmt = $pdo->prepare("SELECT metode_kb, jadwal_kembali FROM layanan_kb WHERE pasien_id = ? ORDER BY tanggal_layanan DESC LIMIT 1");
        $stmt->execute([$pasien_id]);
        $lastKb = $stmt->fetch();

        if ($lastKb) {
             // Asumsikan KB aktif jika jadwal kembali masih di masa depan atau tidak ada jadwal (untuk IUD/Implan)
            if (!$lastKb['jadwal_kembali'] || (new DateTime()) <= (new DateTime($lastKb['jadwal_kembali']))) {
                 return ['status' => 'Akseptor KB Aktif', 'detail' => $lastKb['metode_kb']];
            }
        }
        
        // 4. Jika tidak ada status di atas
        return ['status' => 'Status Umum', 'detail' => 'Tidak dalam masa hamil/nifas/KB aktif'];
    }
    
    /**
     * FUNGSI BARU: Mengambil pasien dengan jadwal kunjungan yang akan datang
     */
    public static function getPatientsWithUpcomingVisits($pdo, $days_ahead = 7) {
        $sql = "
            SELECT DISTINCT 
                p.id, p.no_rm, p.nama_pasien, p.no_telepon, 
                kb.jadwal_kembali as next_visit_date,
                'KB' as visit_type,
                kb.metode_kb as visit_detail
            FROM pasiens p
            JOIN layanan_kb kb ON p.id = kb.pasien_id
            WHERE kb.jadwal_kembali BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)
            AND p.no_telepon IS NOT NULL 
            AND p.no_telepon != ''
            ORDER BY kb.jadwal_kembali ASC
        ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$days_ahead]);
        return $stmt->fetchAll();
    }
    
    /**
     * FUNGSI BARU: Mengambil pasien dengan bayi yang memerlukan imunisasi
     */
    public static function getPatientsWithUpcomingImmunizations($pdo, $days_ahead = 7) {
        // Logika sederhana: bayi yang lahir dalam rentang waktu tertentu mungkin perlu imunisasi
        // Ini bisa disesuaikan dengan jadwal imunisasi yang lebih spesifik
        $sql = "
            SELECT DISTINCT 
                p.id, p.no_rm, p.nama_pasien, p.no_telepon,
                b.nama_bayi, b.tanggal_lahir,
                CASE 
                    WHEN DATEDIFF(CURDATE(), b.tanggal_lahir) BETWEEN 0 AND 1 THEN 'Hepatitis B (HB-0)'
                    WHEN DATEDIFF(CURDATE(), b.tanggal_lahir) BETWEEN 28 AND 35 THEN 'BCG, Polio 1'
                    WHEN DATEDIFF(CURDATE(), b.tanggal_lahir) BETWEEN 56 AND 63 THEN 'DPT-HB-Hib 1, Polio 2'
                    WHEN DATEDIFF(CURDATE(), b.tanggal_lahir) BETWEEN 84 AND 91 THEN 'DPT-HB-Hib 2, Polio 3'
                    WHEN DATEDIFF(CURDATE(), b.tanggal_lahir) BETWEEN 112 AND 119 THEN 'DPT-HB-Hib 3, Polio 4'
                    WHEN DATEDIFF(CURDATE(), b.tanggal_lahir) BETWEEN 270 AND 277 THEN 'Campak'
                    ELSE 'Konsultasi Dokter'
                END as recommended_immunization,
                DATE_ADD(b.tanggal_lahir, INTERVAL 
                    CASE 
                        WHEN DATEDIFF(CURDATE(), b.tanggal_lahir) BETWEEN 0 AND 1 THEN 1
                        WHEN DATEDIFF(CURDATE(), b.tanggal_lahir) BETWEEN 28 AND 35 THEN 30
                        WHEN DATEDIFF(CURDATE(), b.tanggal_lahir) BETWEEN 56 AND 63 THEN 60
                        WHEN DATEDIFF(CURDATE(), b.tanggal_lahir) BETWEEN 84 AND 91 THEN 90
                        WHEN DATEDIFF(CURDATE(), b.tanggal_lahir) BETWEEN 112 AND 119 THEN 120
                        WHEN DATEDIFF(CURDATE(), b.tanggal_lahir) BETWEEN 270 AND 277 THEN 270
                        ELSE 30
                    END DAY
                ) as due_date
            FROM pasiens p
            JOIN bayis b ON p.id = b.pasien_id
            WHERE (
                DATEDIFF(CURDATE(), b.tanggal_lahir) BETWEEN 0 AND 1 OR
                DATEDIFF(CURDATE(), b.tanggal_lahir) BETWEEN 28 AND 35 OR
                DATEDIFF(CURDATE(), b.tanggal_lahir) BETWEEN 56 AND 63 OR
                DATEDIFF(CURDATE(), b.tanggal_lahir) BETWEEN 84 AND 91 OR
                DATEDIFF(CURDATE(), b.tanggal_lahir) BETWEEN 112 AND 119 OR
                DATEDIFF(CURDATE(), b.tanggal_lahir) BETWEEN 270 AND 277
            )
            AND p.no_telepon IS NOT NULL 
            AND p.no_telepon != ''
            ORDER BY due_date ASC
        ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * FUNGSI BARU: Mengambil pasien hamil dengan jadwal ANC yang akan datang
     */
    public static function getPatientsWithUpcomingANC($pdo, $days_ahead = 7) {
        $sql = "
            SELECT DISTINCT 
                p.id, p.no_rm, p.nama_pasien, p.no_telepon, p.hpht,
                DATE_ADD(CURDATE(), INTERVAL ? DAY) as suggested_anc_date,
                'ANC' as visit_type
            FROM pasiens p
            WHERE p.hpht IS NOT NULL 
            AND p.hpht != ''
            AND DATE_ADD(p.hpht, INTERVAL 40 WEEK) > CURDATE()
            AND p.no_telepon IS NOT NULL 
            AND p.no_telepon != ''
            AND p.id NOT IN (
                SELECT DISTINCT anc.pasien_id 
                FROM kunjungan_anc anc 
                WHERE anc.tanggal_kunjungan >= DATE_SUB(CURDATE(), INTERVAL 28 DAY)
            )
            ORDER BY p.hpht ASC
        ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$days_ahead]);
        return $stmt->fetchAll();
    }
}
?>
