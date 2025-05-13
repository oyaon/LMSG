<?php
require_once __DIR__ . '/includes/Database.php';

$db = Database::getInstance();
$mysqli = $db->getConnection();

echo "Database Tables in 'bms':\n";
$result = $mysqli->query("SHOW TABLES");

if ($result) {
    while ($row = $result->fetch_row()) {
        echo "- " . $row[0] . "\n";
    }
} else {
    echo "Error fetching tables: " . $mysqli->error;
}

// Check if specific tables exist
$tables = ['books', 'book', 'tbl_book', 'tbl_books'];
foreach ($tables as $table) {
    $result = $mysqli->query("SHOW TABLES LIKE '$table'");
    echo "Table '$table' exists: " . ($result->num_rows > 0 ? 'Yes' : 'No') . "\n";
}