<?php
/**
 * Migration script to add entry_fee_stat column to users table
 */

require_once(__DIR__ . '/../admin/db-connect.php');

$sql = "ALTER TABLE users 
    ADD COLUMN entry_fee_stat TINYINT(1) NOT NULL DEFAULT 0,
    ADD COLUMN entry_fee_date DATE NULL";

if ($conn->query($sql) === TRUE) {
    echo "Column 'entry_fee_stat' added successfully to users table.";
} else {
    echo "Error adding column: " . $conn->error;
}

$conn->close();
?>
