<?php
// Include initialization file
require_once 'includes/init.php';

// Check database connection
try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    echo "Database connection: SUCCESS\n";
} catch (Exception $e) {
    echo "Database connection: FAILED - " . $e->getMessage() . "\n";
}

// Check if tables exist
$tables = ['users', 'authors', 'all_books', 'borrow_history', 'cart', 'payments'];
foreach ($tables as $table) {
    $result = $db->fetchOne("SELECT 1 FROM information_schema.tables WHERE table_schema = 'bms' AND table_name = ?", "s", [$table]);
    echo "Table '$table': " . ($result ? "EXISTS" : "MISSING") . "\n";
}

// Check admin user
$adminUser = $db->fetchOne("SELECT * FROM users WHERE user_type = 0 LIMIT 1");
echo "Admin user: " . ($adminUser ? "EXISTS (Email: " . $adminUser['email'] . ")" : "MISSING") . "\n";

// Check file permissions
$directories = [
    'uploads/covers',
    'uploads/pdfs',
    'uploads/authors'
];

foreach ($directories as $dir) {
    $fullPath = __DIR__ . '/' . $dir;
    echo "Directory '$dir': " . (is_dir($fullPath) && is_writable($fullPath) ? "WRITABLE" : "NOT WRITABLE") . "\n";
}

// Check PHP version and extensions
echo "PHP Version: " . phpversion() . "\n";
$requiredExtensions = ['mysqli', 'gd', 'session', 'fileinfo'];
foreach ($requiredExtensions as $ext) {
    echo "Extension '$ext': " . (extension_loaded($ext) ? "LOADED" : "MISSING") . "\n";
}

echo "\nApplication should be accessible at: http://localhost/LMS/LMS/\n";