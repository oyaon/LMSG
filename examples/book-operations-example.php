<?php
/**
 * Book Operations Example
 * 
 * This file demonstrates how to use the BookOperations class for book-related database operations
 */

// Include initialization file
require_once '../includes/init.php';

// Example 1: Get all books
echo "<h3>Example 1: Get all books</h3>";
try {
    $books = $bookOps->getAllBooks();
    echo "<pre>";
    print_r($books);
    echo "</pre>";
} catch (Exception $e) {
    Helper::handleException($e);
    echo "<p class='text-danger'>Error: " . Helper::getDatabaseErrorMessage('fetch', 'books') . "</p>";
}

// Example 2: Get book by ID
echo "<h3>Example 2: Get book by ID</h3>";
try {
    $bookId = 1; // Replace with an actual book ID
    $book = $bookOps->getBookById($bookId);
    echo "<pre>";
    print_r($book);
    echo "</pre>";
} catch (Exception $e) {
    Helper::handleException($e);
    echo "<p class='text-danger'>Error: " . Helper::getDatabaseErrorMessage('fetch', 'book') . "</p>";
}

// Example 3: Get books by category
echo "<h3>Example 3: Get books by category</h3>";
try {
    $category = 'Fiction'; // Replace with an actual category
    $books = $bookOps->getBooksByCategory($category, 5);
    echo "<pre>";
    print_r($books);
    echo "</pre>";
} catch (Exception $e) {
    Helper::handleException($e);
    echo "<p class='text-danger'>Error: " . Helper::getDatabaseErrorMessage('fetch', 'books') . "</p>";
}

// Example 4: Search books
echo "<h3>Example 4: Search books</h3>";
try {
    $searchTerm = 'programming'; // Replace with an actual search term
    $books = $bookOps->searchBooks($searchTerm);
    echo "<pre>";
    print_r($books);
    echo "</pre>";
} catch (Exception $e) {
    Helper::handleException($e);
    echo "<p class='text-danger'>Error: " . Helper::getDatabaseErrorMessage('search', 'books') . "</p>";
}

// Example 5: Get latest books
echo "<h3>Example 5: Get latest books</h3>";
try {
    $latestBooks = $bookOps->getLatestBooks(3);
    echo "<pre>";
    print_r($latestBooks);
    echo "</pre>";
} catch (Exception $e) {
    Helper::handleException($e);
    echo "<p class='text-danger'>Error: " . Helper::getDatabaseErrorMessage('fetch', 'books') . "</p>";
}

// Example 6: Get all categories
echo "<h3>Example 6: Get all categories</h3>";
try {
    $categories = $bookOps->getAllCategories();
    echo "<pre>";
    print_r($categories);
    echo "</pre>";
} catch (Exception $e) {
    Helper::handleException($e);
    echo "<p class='text-danger'>Error: " . Helper::getDatabaseErrorMessage('fetch', 'categories') . "</p>";
}

// Example 7: Add a new book (commented out to prevent actual database changes)
echo "<h3>Example 7: Add a new book</h3>";
try {
    // This is just an example - don't actually run this unless you want to add a new book
    /*
    $bookData = [
        'name' => 'Example Book',
        'author' => 'Example Author',
        'category' => 'Fiction',
        'description' => 'This is an example book description.',
        'quantity' => 10,
        'price' => 19.99
    ];
    $newBookId = $bookOps->addBook($bookData);
    echo "New book ID: " . $newBookId;
    */
    echo "<p>Add book code is commented out to prevent actual database changes.</p>";
} catch (Exception $e) {
    Helper::handleException($e);
    echo "<p class='text-danger'>Error: " . Helper::getDatabaseErrorMessage('create', 'book') . "</p>";
}

// Example 8: Update a book (commented out to prevent actual database changes)
echo "<h3>Example 8: Update a book</h3>";
try {
    // This is just an example - don't actually run this unless you want to update a book
    /*
    $bookId = 1; // Replace with an actual book ID
    $bookData = [
        'name' => 'Updated Book Title',
        'author' => 'Updated Author',
        'category' => 'Fiction',
        'description' => 'This is an updated book description.',
        'quantity' => 15,
        'price' => 24.99
    ];
    $result = $bookOps->updateBook($bookId, $bookData);
    echo "Update result: " . ($result ? "Success" : "Failed");
    */
    echo "<p>Update book code is commented out to prevent actual database changes.</p>";
} catch (Exception $e) {
    Helper::handleException($e);
    echo "<p class='text-danger'>Error: " . Helper::getDatabaseErrorMessage('update', 'book') . "</p>";
}

// Example 9: Delete a book (commented out to prevent actual database changes)
echo "<h3>Example 9: Delete a book</h3>";
try {
    // This is just an example - don't actually run this unless you want to delete a book
    /*
    $bookId = 1; // Replace with an actual book ID
    $result = $bookOps->deleteBook($bookId);
    echo "Delete result: " . ($result ? "Success" : "Failed");
    */
    echo "<p>Delete book code is commented out to prevent actual database changes.</p>";
} catch (Exception $e) {
    Helper::handleException($e);
    echo "<p class='text-danger'>Error: " . Helper::getDatabaseErrorMessage('delete', 'book') . "</p>";
}