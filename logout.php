<?php
/**
 * Logout Page
 * 
 * Handles user logout
 */

// Include initialization file
require_once 'includes/init.php';

// Logout user
$user->logout();

// Redirect to login page
Helper::setFlashMessage('success', 'You have been logged out successfully.');
Helper::redirect('login.php');
