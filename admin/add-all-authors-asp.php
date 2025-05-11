<?php
require 'db-connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["authorname"])) {
        echo "Author Name required";
        die();
    } elseif (empty($_POST["authordescription"])) {
        echo "Author Description required";
        die();
    } elseif (empty($_POST["booktype"])) {
        echo "Book Type required";
        die();
    }
}

$author_name = $_POST['authorname'];
$author_description = $_POST['authordescription'];
$book_type = $_POST['booktype'];

// Check if the authors table has a book_type column
$check_column = "SHOW COLUMNS FROM `authors` LIKE 'book_type'";
$column_result = $conn->query($check_column);

if ($column_result->num_rows == 0) {
    // Add book_type column if it doesn't exist
    $add_column = "ALTER TABLE `authors` ADD COLUMN `book_type` VARCHAR(50)";
    $conn->query($add_column);
}

// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare("INSERT INTO authors(name, biography, book_type) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $author_name, $author_description, $book_type);

if ($stmt->execute()) {
    echo "New record created successfully";
    header("location:add-all-authors.php");
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();

?>
