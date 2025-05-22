<?php
// Bootstrap file to load configuration and start session for tests

// Load config constants
require_once __DIR__ . '/../config/config.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
