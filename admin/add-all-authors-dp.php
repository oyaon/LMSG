<?php
require 'db-connect.php';

$id = $_GET['del-id'];

// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare("DELETE FROM `authors` WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Record deleted successfully";
    header("location: add-all-authors.php");
} else {
    echo "Error deleting record: " . $stmt->error;
}
$stmt->close();
?>
