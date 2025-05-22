<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit;
}

// Include necessary files
require_once __DIR__ . '/includes/Database.php';
require_once __DIR__ . '/includes/User.php';
require_once __DIR__ . '/includes/Notification.php';
require_once __DIR__ . '/includes/Helper.php';

// Initialize objects
$user = new User();
$notification = new Notification();

// Mark all notifications as read
$success = $notification->markAllAsRead($user->getId());

// Set flash message
if ($success) {
    Helper::setFlashMessage('success', 'All notifications marked as read.');
} else {
    Helper::setFlashMessage('error', 'Failed to mark notifications as read.');
}

// Redirect back to the referring page or to the home page
$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
header("Location: $referrer");
exit;
?>