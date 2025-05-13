<?php
session_start();
require_once 'includes/init.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !Helper::validateCsrfToken($_POST['csrf_token'], 'registration_form')) {
        echo "Invalid CSRF token.";
        die();
    }

    // Check if all required fields are filled
    if (empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_password'])) {
        echo "All fields are required.";
        die();
    }

    // Sanitize input data
    $userData = [
        'first_name' => trim($_POST['firstname']),
        'last_name' => trim($_POST['lastname']),
        'user_name' => trim($_POST['username']),
        'email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
        'password' => $_POST['password'],
        'confirm_password' => $_POST['confirm_password']
    ];

    // Check if passwords match
    if ($userData['password'] !== $userData['confirm_password']) {
        echo "Passwords do not match.";
        die();
    }

    // Use User class to register
    $user = new User();
    $userId = $user->register($userData);

    if ($userId) {
        echo "Registration Successful.";
    } else {
        echo "Registration failed. Email may already be registered.";
    }
}
?>
