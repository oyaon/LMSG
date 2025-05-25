<?php
require_once 'includes/init.php';

// Get the database connection
$connection = $db->getConnection();

// Check the structure of the payments table
$result = $connection->query("DESCRIBE payments");

$output = "<h2>Payments Table Structure:</h2>\n";
$output .= "<table border='1'>\n";
$output .= "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>\n";

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $output .= "<tr>";
        $output .= "<td>" . $row['Field'] . "</td>";
        $output .= "<td>" . $row['Type'] . "</td>";
        $output .= "<td>" . $row['Null'] . "</td>";
        $output .= "<td>" . $row['Key'] . "</td>";
        $output .= "<td>" . $row['Default'] . "</td>";
        $output .= "<td>" . $row['Extra'] . "</td>";
        $output .= "</tr>\n";
    }
} else {
    $output .= "<tr><td colspan='6'>Error: " . $connection->error . "</td></tr>\n";
}

$output .= "</table>";

// Write to a file
file_put_contents('table_structure.html', $output);
echo "Table structure has been written to table_structure.html";
?>