<?php
// File: app/models/User.php
// Model untuk mengelola data pengguna (users).

class User {

    // Mengambil semua data pengguna
    public static function getAll($pdo) {
        $stmt = $pdo->query("SELECT id, name, email, role, created_at FROM users ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    // Mencari pengguna berdasarkan ID
    public static function findById($pdo, $id) {
        $stmt = $pdo->prepare("SELECT id, name, email, role, profile_picture FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Membuat pengguna baru
    public static function create($pdo, $data) {
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['name'],
            $data['email'],
            $hashed_password,
            $data['role']
        ]);
        return $pdo->lastInsertId();
    }

    // Memperbarui data pengguna
    public static function update($pdo, $id, $data) {
        // Handle profile picture upload
        $profilePicturePath = $data['current_profile_picture'] ?? '';

        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            require_once ROOT_PATH . '/app/core/upload.php';

            // Delete old profile picture if exists
            if (!empty($profilePicturePath)) {
                deleteOldImage($profilePicturePath);
            }

            // Upload new profile picture
            $uploadResult = handleImageUpload($_FILES['profile_picture'], 'uploads/profiles/', 'profile_');
            if ($uploadResult['success']) {
                $profilePicturePath = $uploadResult['path'];
            } else {
                // Handle upload error - you might want to set a session error here
                error_log('Profile picture upload failed: ' . $uploadResult['error']);
            }
        }

        // Cek apakah password baru diisi atau tidak
        if (!empty($data['password'])) {
            // Jika ada password baru, hash dan update
            $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
            $sql = "UPDATE users SET name = ?, email = ?, role = ?, password = ?, profile_picture = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$data['name'], $data['email'], $data['role'], $hashed_password, $profilePicturePath, $id]);
        } else {
            // Jika tidak ada password baru, update data lainnya saja
            $sql = "UPDATE users SET name = ?, email = ?, role = ?, profile_picture = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$data['name'], $data['email'], $data['role'], $profilePicturePath, $id]);
        }
        return $stmt->rowCount();
    }

    // Menghapus pengguna
    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

    // Memeriksa apakah email sudah ada (untuk validasi)
    public static function isEmailExists($pdo, $email, $exclude_id = null) {
        $sql = "SELECT COUNT(*) FROM users WHERE email = ?";
        $params = [$email];
        if ($exclude_id) {
            $sql .= " AND id != ?";
            $params[] = $exclude_id;
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
}
?>
