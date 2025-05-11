<?php
require 'db-connect.php';
require_once '../includes/init.php';

// Validate form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    
    // Validate required fields
    if (empty($_POST["id"])) {
        $errors[] = "Book ID is required";
    }
    if (empty($_POST["bookname"])) {
        $errors[] = "Book Name is required";
    }
    if (empty($_POST["bookauthor"])) {
        $errors[] = "Book Author is required";
    }
    if (empty($_POST["bookcategory"])) {
        $errors[] = "Book Category is required";
    }
    if (empty($_POST["bookquantity"])) {
        $errors[] = "Book Quantity is required";
    }
    if (empty($_POST["bookprice"])) {
        $errors[] = "Book Price is required";
    }
    
    // If there are validation errors, redirect back with error messages
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("location: add-all-books-efp.php?edit-id=" . $_POST["id"]);
        exit;
    }
    
    try {
        // Sanitize input data
        $id = (int)$_POST["id"];
        $bookData = [
            'name' => Helper::sanitize($_POST['bookname']),
            'author' => Helper::sanitize($_POST['bookauthor']),
            'category' => Helper::sanitize($_POST['bookcategory']),
            'description' => Helper::sanitize($_POST['bookdescription']),
            'quantity' => (int)$_POST['bookquantity'],
            'price' => (float)$_POST['bookprice']
        ];
        
        // Get current book data
        $stmt = $conn->prepare("SELECT pdf FROM all_books WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("Book not found with ID: $id");
        }
        
        $currentBook = $result->fetch_assoc();
        
        // Handle file upload
        $pdfFileName = $currentBook['pdf'];
        if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0 && $_FILES['pdf']['name'] != "") {
            // Delete old file if exists
            if (!empty($currentBook['pdf'])) {
                $oldFilePath = "../pdfs/" . $currentBook['pdf'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            
            // Generate a unique filename
            $pdfFileName = uniqid() . '_' . basename($_FILES['pdf']['name']);
            $targetPath = "../pdfs/" . $pdfFileName;
            
            // Check file size (limit to 10MB)
            if ($_FILES['pdf']['size'] > 10 * 1024 * 1024) {
                throw new Exception("File size exceeds the maximum allowed size (10MB)");
            }
            
            // Move uploaded file
            if (!move_uploaded_file($_FILES['pdf']['tmp_name'], $targetPath)) {
                throw new Exception("Failed to upload PDF file");
            }
        }
        
        // Use prepared statement for update
        $stmt = $conn->prepare("UPDATE all_books SET 
                               name = ?, 
                               author = ?, 
                               category = ?, 
                               description = ?, 
                               quantity = ?, 
                               price = ?, 
                               pdf = ? 
                               WHERE id = ?");
        
        $stmt->bind_param("ssssiisi", 
            $bookData['name'], 
            $bookData['author'], 
            $bookData['category'], 
            $bookData['description'], 
            $bookData['quantity'], 
            $bookData['price'], 
            $pdfFileName,
            $id
        );
        
        if ($stmt->execute()) {
            // Set success message
            $_SESSION['success'] = "Book updated successfully!";
            header("location: add-all-books.php");
            exit;
        } else {
            throw new Exception("Failed to update book: " . $stmt->error);
        }
    } catch (Exception $e) {
        // Log the error
        Helper::logError("Error updating book: " . $e->getMessage(), __FILE__, __LINE__);
        
        // Set error message
        $_SESSION['errors'] = ["An error occurred while updating the book: " . $e->getMessage()];
        header("location: add-all-books-efp.php?edit-id=" . $_POST["id"]);
        exit;
    }
} else {
    // Not a POST request, redirect to books list
    header("location: add-all-books.php");
    exit;
}
?>
