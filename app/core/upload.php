<?php
// File: app/core/upload.php
// Helper functions for handling file uploads

/**
 * Handle image upload with validation
 * @param array $file The $_FILES array element
 * @param string $uploadDir The directory to upload to
 * @param string $prefix Prefix for the filename
 * @return array Result with success status and path or error message
 */
function handleImageUpload($file, $uploadDir = 'uploads/', $prefix = 'img_') {
    $result = ['success' => false, 'path' => '', 'error' => ''];

    // Check if file was uploaded
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        $result['error'] = 'File tidak diupload atau ada error.';
        return $result;
    }

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes)) {
        $result['error'] = 'Tipe file tidak didukung. Gunakan JPG, PNG, atau GIF.';
        return $result;
    }

    // Validate file size (max 2MB)
    $maxSize = 2 * 1024 * 1024; // 2MB
    if ($file['size'] > $maxSize) {
        $result['error'] = 'Ukuran file terlalu besar. Maksimal 2MB.';
        return $result;
    }

    // Create upload directory if it doesn't exist
    $fullUploadDir = ROOT_PATH . '/' . $uploadDir;
    if (!is_dir($fullUploadDir)) {
        mkdir($fullUploadDir, 0755, true);
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = $prefix . time() . '_' . uniqid() . '.' . $extension;
    $filepath = $fullUploadDir . $filename;

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        $result['success'] = true;
        $result['path'] = '/' . $uploadDir . $filename;
        return $result;
    } else {
        $result['error'] = 'Gagal menyimpan file.';
        return $result;
    }
}

/**
 * Delete old image file
 * @param string $imagePath The path to the image file to delete
 * @return bool True if deleted successfully or file doesn't exist
 */
function deleteOldImage($imagePath) {
    if (!empty($imagePath) && file_exists(ROOT_PATH . $imagePath)) {
        return unlink(ROOT_PATH . $imagePath);
    }
    return true; // File doesn't exist, consider as success
}
?>
