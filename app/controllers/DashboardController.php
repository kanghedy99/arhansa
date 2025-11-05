<?php
// File: app/controllers/DashboardController.php (Enhanced)

check_login();

require_once ROOT_PATH . '/app/models/Pasien.php';
require_once ROOT_PATH . '/app/models/Anc.php';
require_once ROOT_PATH . '/app/models/Inc.php';
require_once ROOT_PATH . '/app/models/Nifas.php';
require_once ROOT_PATH . '/app/models/Kb.php';
require_once ROOT_PATH . '/app/models/KunjunganBayi.php';
require_once ROOT_PATH . '/app/models/Laporan.php';

// Mengambil data statistik dasar
$total_pasien = Pasien::countAll($pdo);
$total_anc_bulan_ini = Anc::countThisMonth($pdo);
$total_inc_bulan_ini = Inc::countThisMonth($pdo);
$total_nifas_bulan_ini = Nifas::countThisMonth($pdo);
$total_kb_bulan_ini = Kb::countThisMonth($pdo);
$total_kunjungan_bayi_bulan_ini = KunjunganBayi::countThisMonth($pdo);

// Mengambil statistik layanan bulanan
$monthly_service_stats = Laporan::getMonthlyServiceStats($pdo);

// Mengambil ringkasan kunjungan harian (7 hari terakhir)
$daily_visit_summary = Laporan::getDailyVisitSummary($pdo, 7);

// Mengambil jadwal mendatang (untuk 30 hari ke depan)
$jadwal_mendatang = Laporan::getJadwalMendatang($pdo, 30);

// Mengambil statistik kunjungan untuk chart (30 hari terakhir)
$visit_statistics = Laporan::getVisitStatistics($pdo);

// Memuat file view untuk dashboard
include ROOT_PATH . '/views/dashboard.php';
?>
