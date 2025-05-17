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
        // Log the user in by setting session variables
        $_SESSION['user_id'] = $userId;
        $_SESSION['email'] = $userData['email'];
        $_SESSION['user_type'] = isset($userData['user_type']) ? $userData['user_type'] : 1;
        $_SESSION['user_name'] = $userData['user_name'];  // Set user_name session variable

        // Redirect to user profile page after successful registration and login
        header('Location: user_profile.php');
        exit;
    } else {
        echo "Registration failed. Email may already be registered.";
    }
}
?>
