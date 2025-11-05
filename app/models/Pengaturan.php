<?php
// File: app/models/Pengaturan.php
// Model untuk mengelola data pengaturan aplikasi.

class Pengaturan {

    /**
     * Mengambil semua data pengaturan (selalu hanya 1 baris).
     * @param PDO $pdo Objek koneksi database.
     * @return array Data pengaturan.
     */
    public static function get($pdo) {
        $stmt = $pdo->prepare("SELECT * FROM pengaturan WHERE id = 1");
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Memperbarui data pengaturan.
     * @param PDO $pdo Objek koneksi database.
     * @param array $data Data dari form.
     * @return int Jumlah baris yang terpengaruh.
     */
    public static function update($pdo, $data) {
        // Handle logo upload
        $logoPath = $data['current_logo_klinik'] ?? '';

        if (isset($_FILES['logo_klinik']) && $_FILES['logo_klinik']['error'] === UPLOAD_ERR_OK) {
            require_once ROOT_PATH . '/app/core/upload.php';

            // Delete old logo if exists
            if (!empty($logoPath)) {
                deleteOldImage($logoPath);
            }

            // Upload new logo
            $uploadResult = handleImageUpload($_FILES['logo_klinik'], 'uploads/logos/', 'logo_');
            if ($uploadResult['success']) {
                $logoPath = $uploadResult['path'];
            } else {
                // Handle upload error
                error_log('Logo upload failed: ' . $uploadResult['error']);
            }
        }

        $sql = "UPDATE pengaturan SET
                    nama_klinik = ?,
                    alamat_klinik = ?,
                    telepon_klinik = ?,
                    email_klinik = ?,
                    logo_klinik = ?
                WHERE id = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['nama_klinik'],
            $data['alamat_klinik'],
            $data['telepon_klinik'],
            $data['email_klinik'],
            $logoPath
        ]);
        return $stmt->rowCount();
    }
}
?>
