<?php
require 'db-connect.php';
require_once '../includes/init.php';

// Validate form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    
    // Validate required fields
    if (empty($_POST["bookname"])) {
        $errors[] = "Book Name required";
    }
    if (empty($_POST["bookauthor"])) {
        $errors[] = "Book Author required";
    }
    if (empty($_POST["bookcategory"])) {
        $errors[] = "Book Category required";
    }
    if (empty($_POST["bookquantity"])) {
        $errors[] = "Book Quantity required";
    }
    if (empty($_POST["bookprice"])) {
        $errors[] = "Book Price required";
    }
    
    // If there are validation errors, redirect back with error messages
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("location:add-all-books.php");
        exit;
    }
    
    try {
        // Sanitize input data
        $bookData = [
            'name' => Helper::sanitize($_POST['bookname']),
            'author' => Helper::sanitize($_POST['bookauthor']),
            'category' => Helper::sanitize($_POST['bookcategory']),
            'description' => Helper::sanitize($_POST['bookdescription']),
            'quantity' => (int)$_POST['bookquantity'],
            'price' => (float)$_POST['bookprice']
        ];
        
        // Handle file upload
        if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0) {
            // Generate a unique filename
            $pdfFileName = uniqid() . '_' . basename($_FILES['pdf']['name']);
            $targetPath = "../uploads/pdfs/" . $pdfFileName;
            
            // Check file size (limit to 10MB)
            if ($_FILES['pdf']['size'] > 10 * 1024 * 1024) {
                throw new Exception("File size exceeds the maximum allowed size (10MB)");
            }
            
            // Move uploaded file
            if (!move_uploaded_file($_FILES['pdf']['tmp_name'], $targetPath)) {
                throw new Exception("Failed to upload PDF file");
            }
            
            $bookData['pdf'] = $pdfFileName;
        } else {
            $bookData['pdf'] = '';
        }
        
        // Use prepared statement for insertion
        $stmt = $mysqli->prepare("INSERT INTO all_books 
                               (name, author, category, description, pdf, quantity, price, cover_image) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Define coverImage before binding parameters
        $coverImage = ''; // Empty cover image for now
        
        $stmt->bind_param("sssssids", 
            $bookData['name'], 
            $bookData['author'], 
            $bookData['category'], 
            $bookData['description'], 
            $bookData['pdf'], 
            $bookData['quantity'], 
            $bookData['price'], 
            $coverImage
        );
        
        // Handle cover image upload if provided
        if (isset($_FILES['coverimage']) && $_FILES['coverimage']['error'] == 0) {
            $coverFileName = uniqid() . '_' . basename($_FILES['coverimage']['name']);
            $coverTargetPath = "../uploads/covers/" . $coverFileName;
            
            // Check file size (limit to 5MB)
            if ($_FILES['coverimage']['size'] > 5 * 1024 * 1024) {
                throw new Exception("Cover image size exceeds the maximum allowed size (5MB)");
            }
            
            // Check file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($_FILES['coverimage']['type'], $allowedTypes)) {
                throw new Exception("Invalid cover image format. Only JPG, PNG, and GIF are allowed.");
            }
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['coverimage']['tmp_name'], $coverTargetPath)) {
                $coverImage = $coverFileName;
            } else {
                throw new Exception("Failed to upload cover image");
            }
        }
        
        if ($stmt->execute()) {
            // Set success message
            $_SESSION['success'] = "Book added successfully!";
            header("location:add-all-books.php");
            exit;
        } else {
            throw new Exception("Failed to add book: " . $stmt->error);
        }
    } catch (Exception $e) {
        // Log the error
        Helper::logError("Error adding book: " . $e->getMessage(), __FILE__, __LINE__);
        
        // Set error message
        $_SESSION['errors'] = ["An error occurred while adding the book: " . $e->getMessage()];
        header("location:add-all-books.php");
        exit;
    }
}
?>
