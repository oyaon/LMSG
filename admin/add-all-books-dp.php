<?php
require 'db-connect.php';
require_once '../includes/init.php';

// Check if ID is provided
if (!isset($_GET['del-id']) || empty($_GET['del-id'])) {
    $_SESSION['errors'] = "No book ID provided for deletion";
    header("location: add-all-books.php");
    exit;
}

try {
    // Get the book ID
    $id = (int)$_GET['del-id'];
    
    // Get book information to delete associated files
    $stmt = $conn->prepare("SELECT pdf FROM all_books WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        
        // Delete PDF file if exists
        if (!empty($book['pdf'])) {
            $pdfPath = "../pdfs/" . $book['pdf'];
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }
        }
        
        // Delete the book record
        $stmt = $conn->prepare("DELETE FROM all_books WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Book deleted successfully";
        } else {
            throw new Exception("Error deleting book: " . $stmt->error);
        }
    } else {
        throw new Exception("Book not found with ID: $id");
    }
    
    header("location: add-all-books.php");
    exit;
} catch (Exception $e) {
    // Log the error
    Helper::logError("Error deleting book: " . $e->getMessage(), __FILE__, __LINE__);
    
    // Set error message
    $_SESSION['errors'] = "An error occurred while deleting the book: " . $e->getMessage();
    header("location: add-all-books.php");
    exit;
}
?>
