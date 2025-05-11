<?php
/**
 * Create Admin User Script
 * 
 * This script creates an admin user in the database
 */

// Include initialization file
require_once 'includes/init.php';

// Admin user data
$adminData = [
    'first_name' => 'Admin',
    'last_name' => 'User',
    'user_name' => 'admin',
    'email' => 'admin@example.com',
    'password' => 'admin123',
    'user_type' => 0 // Admin user
];

// Check if admin user already exists
$existingAdmin = $db->fetchOne(
    "SELECT id FROM users WHERE email = ?",
    "s",
    [$adminData['email']]
);

if ($existingAdmin) {
    echo "Admin user already exists!";
} else {
    // Register admin user
    $result = $user->register($adminData);
    
    if ($result) {
        echo "Admin user created successfully!";
    } else {
        echo "Failed to create admin user.";
    }
}