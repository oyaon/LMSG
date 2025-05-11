<?php
// Database connection configuration
$servername = "localhost";  // Your database server
$username = "root";         // Your database username (default for XAMPP)
$password = "";             // Your database password (leave blank if none)
$dbname = "bms";            // Your database name (adjust accordingly)

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
