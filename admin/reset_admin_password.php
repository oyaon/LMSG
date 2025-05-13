<?php
/**
 * Reset Admin Password Script
 * 
 * This script resets the admin user's password to a new value.
 */

// Include initialization file
require_once __DIR__ . '/../includes/init.php';

// New password for admin
$newPassword = 'admin123';

// Load admin user by email
if ($user->loadUserByEmail('admin@example.com')) {
    // Update password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $result = $db->update(
        "UPDATE users SET password = ? WHERE email = ?",
        "ss",
        [$hashedPassword, 'admin@example.com']
    );

    if ($result) {
        echo "Admin password has been reset successfully to '{$newPassword}'. Please login using this password.";
    } else {
        echo "Failed to reset admin password.";
    }
} else {
    echo "Admin user not found.";
}
?>
