<?php
/**
 * Logout Page
 * 
 * Handles user logout
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
require_once __DIR__ . '/includes/Database.php';
require_once __DIR__ . '/includes/Helper.php';

try {
    // Include initialization file if it exists
    if (file_exists(__DIR__ . '/includes/init.php')) {
        require_once __DIR__ . '/includes/init.php';
        
        // Try to use the User class if available
        if (class_exists('User')) {
            require_once __DIR__ . '/includes/User.php';
            $user = new User();
            $user->logout();
        }
    }
} catch (Exception $e) {
    // Fallback: Destroy session manually
    $_SESSION = array();
    
    // If a session cookie is used, destroy it
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Finally, destroy the session
    session_destroy();
}

// Set flash message if Helper class is available
if (class_exists('Helper')) {
    Helper::setFlashMessage('success', 'You have been logged out successfully.');
    
    // Check which login page exists and redirect to it
    if (file_exists(__DIR__ . '/login_page.php')) {
        Helper::redirect('login_page.php');
    } else {
        Helper::redirect('login.php');
    }
} else {
    // Fallback: Redirect directly
    if (file_exists(__DIR__ . '/login_page.php')) {
        header('Location: login_page.php');
    } else {
        header('Location: login.php');
    }
    exit;
}
