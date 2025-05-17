<?php
// Router script for PHP built-in server to handle routing and serve static files

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

$requested = __DIR__ . $uri;

// Serve the requested resource as-is if it exists and is a file
if ($uri !== '/' && file_exists($requested) && !is_dir($requested)) {
    return false;
}

// Otherwise, route all requests to index.php or handle accordingly
require_once __DIR__ . '/index.php';
