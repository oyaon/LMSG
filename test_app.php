<?php
// This script will test if the application is working correctly
// by directly including the index.php file

// Start output buffering to capture any output
ob_start();

// Include the index.php file
include 'index.php';

// Get the output
$output = ob_get_clean();

// Display the first 1000 characters of the output
echo substr($output, 0, 1000);
echo "\n\n...output truncated...\n\n";

// Check if there were any errors
$errors = error_get_last();
if ($errors) {
    echo "Errors encountered:\n";
    print_r($errors);
}
?>