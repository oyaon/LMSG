<?php
/**
 * Unified Initialization File
 * 
 * Loads all required classes and starts the session
 * Consolidates includes/init.php and includes/init_simple.php
 */

// Load configuration
require_once __DIR__ . '/../config/config.php';

// Session settings - moved here from config.php to avoid headers already sent warnings
if (session_status() == PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
    session_start();
}

// Load Database class and initialize singleton
require_once __DIR__ . '/Database.php';
$db = Database::getInstance();

// Load helper and other classes
require_once __DIR__ . '/Helper.php';
require_once __DIR__ . '/DatabaseOperations.php';
require_once __DIR__ . '/User.php';
require_once __DIR__ . '/Book.php';
require_once __DIR__ . '/BookOperations.php';
require_once __DIR__ . '/Borrow.php';
require_once __DIR__ . '/Cart.php';
require_once __DIR__ . '/Author.php';
require_once __DIR__ . '/DatabaseBackup.php';
require_once __DIR__ . '/components.php';

// Initialize objects
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
