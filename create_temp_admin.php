<?php
require_once 'includes/init.php';

$email = 'admin@example.com';
$password = 'admin123';
$firstName = 'Admin';
$lastName = 'User';
$userName = 'admin';

// Check if admin user already exists
$existingAdmin = $db->fetchOne("SELECT id FROM users WHERE email = ?", "s", [$email]);
if ($existingAdmin) {
    echo "Admin user already exists with email: $email";
    exit;
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert admin user
$sql = "INSERT INTO users (first_name, last_name, user_name, email, password, user_type) VALUES (?, ?, ?, ?, ?, ?)";
$params = [$firstName, $lastName, $userName, $email, $hashedPassword, 0]; // user_type=0 for admin

$insertId = $db->insert($sql, "sssssi", $params);

if ($insertId) {
    echo "Admin user created successfully with ID: $insertId";
} else {
    echo "Failed to create admin user.";
}
?>
