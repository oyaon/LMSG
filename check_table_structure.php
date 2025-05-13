<?php
require_once __DIR__ . '/includes/Database.php';

$db = Database::getInstance();
$mysqli = $db->getConnection();

$tables = ['all_books', 'borrow_history', 'users'];

foreach ($tables as $table) {
    echo "Structure of table '$table':\n";
    $result = $mysqli->query("DESCRIBE $table");
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
    } else {
        echo "Error fetching structure: " . $mysqli->error;
    }
    echo "\n";
}