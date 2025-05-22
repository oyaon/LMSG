<?php
/**
 * Application Configuration
 * 
 * This file contains all configuration settings for the LMS application
 */

// Session settings - removed to avoid duplication and headers already sent warnings

// Environment setting
define('ENVIRONMENT', 'development'); // Options: 'development', 'production'

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bms');

// Application settings
define('APP_NAME', 'Library Management System');
define('APP_URL', 'http://localhost/LMS/LMS');
define('APP_VERSION', '2.0.0');

// File upload paths
define('UPLOAD_DIR', __DIR__ . '/../uploads');
define('BOOK_COVERS_DIR', UPLOAD_DIR . '/covers');
define('PDF_FILES_DIR', UPLOAD_DIR . '/pdfs');
define('AUTHOR_IMAGES_DIR', UPLOAD_DIR . '/authors');

// Ensure upload directories exist
if (!file_exists(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
if (!file_exists(BOOK_COVERS_DIR)) mkdir(BOOK_COVERS_DIR, 0755, true);
if (!file_exists(PDF_FILES_DIR)) mkdir(PDF_FILES_DIR, 0755, true);
if (!file_exists(AUTHOR_IMAGES_DIR)) mkdir(AUTHOR_IMAGES_DIR, 0755, true);

// Error reporting is now handled in init.php based on ENVIRONMENT constant

// Time zone
date_default_timezone_set('Asia/Dhaka'); // Adjust to your timezone

// Maximum file upload size (in bytes)
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
