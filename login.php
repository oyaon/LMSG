<?php
/**
 * Login Processing
 * 
 * Handles user authentication and redirects
 */

// Include initialization file
require_once 'includes/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $redirectUrl = isset($_POST['redirect']) ? Helper::sanitize($_POST['redirect']) : 'index.php';
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !Helper::validateCsrfToken($_POST['csrf_token'], 'login_form')) {
        $errors = ['Invalid form submission. Please try again.'];
        Helper::setFlashMessage('error', 'Invalid form submission. Please try again.');
        Helper::redirect('login_page.php' . ($redirectUrl !== 'index.php' ? '?redirect=' . urlencode($redirectUrl) : ''));
    } else {
        // Sanitize input
        $email = Helper::sanitize($_POST['email']);
        $password = $_POST['password']; // Don't sanitize password

        // Validate input
        $errors = [];

        if (empty($email)) {
            $errors[] = 'Email is required';
            Helper::setFlashMessage('error', 'Email is required');
        } elseif (!Helper::validateEmail($email)) {
            $errors[] = 'Invalid email format';
            Helper::setFlashMessage('error', 'Invalid email format');
        }

        if (empty($password)) {
            $errors[] = 'Password is required';
            Helper::setFlashMessage('error', 'Password is required');
        }
        if (!empty($errors)) {
            Helper::redirect('login_page.php' . ($redirectUrl !== 'index.php' ? '?redirect=' . urlencode($redirectUrl) : ''));
        }
        // Attempt login if no errors
        if (empty($errors)) {
            $authResult = $user->authenticate($email, $password);

            if ($authResult === true) {
                // Handle "remember me" functionality
                if (isset($_POST['remember_me']) && $_POST['remember_me'] === 'on') {
                    $token = $user->createRememberMeToken($user->getId());
                    if ($token) {
                        setcookie(
                            'remember_me_token',
                            $token,
                            time() + 60 * 60 * 24 * 30, // 30 days
                            '/',
                            '',
                            isset($_SERVER['HTTPS']),
                            true
                        );
                    }
                } else {
                    // Clear remember me cookie if exists
                    if (isset($_COOKIE['remember_me_token'])) {
                        setcookie('remember_me_token', '', time() - 3600, '/', '', isset($_SERVER['HTTPS']), true);
                    }
                }

                // Successful login
                // Redirect based on user type
                if ($user->isAdmin()) {
                    Helper::redirect('admin/index.php');
                } elseif ($redirectUrl && $redirectUrl !== 'index.php') {
                    Helper::redirect($redirectUrl);
                } else {
                    Helper::redirect('index.php');
                }
            } elseif (is_array($authResult) && isset($authResult['exceeded'])) {
                // Rate limit exceeded
                $waitMinutes = ceil($authResult['waitTime'] / 60);
                $errors[] = "Too many failed login attempts. Please try again after {$waitMinutes} minute(s).";
                Helper::setFlashMessage('error', "Too many failed login attempts. Please try again after {$waitMinutes} minute(s).");
            } else {
                // Failed login
                $errors[] = 'Invalid email or password';
                Helper::setFlashMessage('error', 'Invalid email or password.');
            }
        }
        Helper::redirect('login_page.php' . ($redirectUrl !== 'index.php' ? '?redirect=' . urlencode($redirectUrl) : ''));
    }
} else {
    // If not a POST request, redirect to the login page
    Helper::redirect('login_page.php');
}
?>
