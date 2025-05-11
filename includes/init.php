<?php
/**
 * Initialization File
 * 
 * Loads all required classes and starts the session
 */

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Load configuration
require_once __DIR__ . '/../config/config.php';

// Load classes
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Helper.php';
require_once __DIR__ . '/DatabaseOperations.php';
require_once __DIR__ . '/User.php';
require_once __DIR__ . '/Book.php';
require_once __DIR__ . '/BookOperations.php';
require_once __DIR__ . '/Borrow.php';
require_once __DIR__ . '/Cart.php';
require_once __DIR__ . '/Author.php';
require_once __DIR__ . '/DatabaseBackup.php';

// Initialize objects
$db = Database::getInstance();
$dbOps = new DatabaseOperations();
$user = new User();
$book = new Book();
$bookOps = new BookOperations();
$borrow = new Borrow();
$cart = new Cart();
$author = new Author();

// Set error reporting based on environment
if (defined('ENVIRONMENT') && ENVIRONMENT === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Function to autoload classes
function autoloadClass($className) {
    $file = __DIR__ . "/$className.php";
    if (file_exists($file)) {
        require_once $file;
    }
}

// Register autoloader
spl_autoload_register('autoloadClass');