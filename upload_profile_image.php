<?php
require_once 'includes/init.php';

$user = new User();

if (!$user->isLoggedIn()) {
    header('Location: login.php?redirect=user_profile.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    $uploadDir = 'uploads/profile_images/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $file = $_FILES['profile_image'];
    $fileName = basename($file['name']);
    $fileTmp = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 2 * 1024 * 1024; // 2MB

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $fileTmp);
    finfo_close($finfo);

    if ($fileError !== UPLOAD_ERR_OK) {
        $_SESSION['flash_message'] = 'Error uploading file.';
        $_SESSION['flash_type'] = 'danger';
        header('Location: user_profile.php');
        exit;
    }

    if (!in_array($mimeType, $allowedTypes)) {
        $_SESSION['flash_message'] = 'Invalid file type. Only JPG, PNG, and GIF are allowed.';
        $_SESSION['flash_type'] = 'danger';
        header('Location: user_profile.php');
        exit;
    }

    if ($fileSize > $maxSize) {
        $_SESSION['flash_message'] = 'File size exceeds 2MB limit.';
        $_SESSION['flash_type'] = 'danger';
        header('Location: user_profile.php');
        exit;
    }

    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = 'user_' . $user->getId() . '_' . time() . '.' . $ext;
    $destination = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmp, $destination)) {
        // Update user profile_image in database
        $db = Database::getInstance();
        $db->update(
            "UPDATE users SET profile_image = ? WHERE id = ?",
            "si",
            [$newFileName, $user->getId()]
        );

        $_SESSION['flash_message'] = 'Profile image updated successfully.';
        $_SESSION['flash_type'] = 'success';
    } else {
        $_SESSION['flash_message'] = 'Failed to move uploaded file.';
        $_SESSION['flash_type'] = 'danger';
    }
} else {
    $_SESSION['flash_message'] = 'No file uploaded.';
    $_SESSION['flash_type'] = 'danger';
}

header('Location: user_profile.php');
exit;
