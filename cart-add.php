<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("./admin/db-connect.php");
session_start();

if (!isset($_SESSION["email"])) {
    echo '<script type="text/javascript">
        alert("Login first!");
        window.location.assign("login_page.php");
    </script>';
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<script type="text/javascript">
        alert("Invalid book ID.");
        window.location.assign("search-books.php");
    </script>';
    exit();
}

$book_id = (int)$_GET['id'];
$user_email = $_SESSION["email"];

// Check book availability
$bookQuery = "SELECT quantity FROM all_books WHERE id = ?";
$stmt = $conn->prepare($bookQuery);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$bookResult = $stmt->get_result();
$book = $bookResult->fetch_assoc();

if (!$book || $book['quantity'] <= 0) {
    echo '<script type="text/javascript">
        alert("Book is out of stock.");
        window.location.assign("search-books.php");
    </script>';
    exit();
}

// Add book to cart and update stock
$conn->begin_transaction();
try {
    $today = date('Y-m-d');
    $insertStmt = $conn->prepare("INSERT INTO cart (user_email, book_id, date, status) VALUES (?, ?, ?, 0)");
    $insertStmt->bind_param("sis", $user_email, $book_id, $today);
    $insertStmt->execute();

    $updateStmt = $conn->prepare("UPDATE all_books SET quantity = quantity - 1 WHERE id = ?");
    $updateStmt->bind_param("i", $book_id);
    $updateStmt->execute();

    $conn->commit();

    echo '<script type="text/javascript">
        alert("Book added to cart.");
        window.location.assign("cart.php");
    </script>';
    exit();
} catch (Exception $e) {
    $conn->rollback();
    echo '<script type="text/javascript">
        alert("Failed to add book to cart.");
        window.history.back();
    </script>';
    exit();
}
?>
