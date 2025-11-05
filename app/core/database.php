<?php
// File: app/core/database.php
// Berfungsi untuk membuat koneksi ke database menggunakan PDO yang lebih aman.

// Pengaturan Database
$host = 'localhost';
$db_name = 'arhansa'; // Pastikan nama ini sama dengan database yang Anda buat
$username = 'arhansa';
$password = 'arhansa'; // Sesuaikan jika database Anda memiliki password

try {
    // Buat objek PDO
    $pdo = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    
    // Set error mode ke exception untuk penanganan error yang lebih baik
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set fetch mode default ke associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Tampilkan pesan error jika koneksi gagal
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
