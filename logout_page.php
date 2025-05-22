<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to the main logout.php file
header("Location: logout.php");
exit();
?>