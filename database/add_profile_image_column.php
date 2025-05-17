<?php
require_once __DIR__ . '/../includes/init.php';

$db = Database::getInstance();
$conn = $db->getConnection();

$sql = "ALTER TABLE users ADD COLUMN profile_image VARCHAR(255) DEFAULT NULL";

if ($conn->query($sql) === TRUE) {
    echo "Column 'profile_image' added successfully.";
} else {
    echo "Error adding column: " . $conn->error;
}
