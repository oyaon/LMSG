<?php
/**
 * Database Operations Example
 * 
 * This file demonstrates how to use the DatabaseOperations class for common database tasks
 */

// Include initialization file
require_once '../includes/init.php';

// Example 1: Get all records from a table
echo "<h3>Example 1: Get all authors</h3>";
try {
    $authors = $dbOps->getAll('authors', 'name', 'ASC');
    echo "<pre>";
    print_r($authors);
    echo "</pre>";
} catch (Exception $e) {
    Helper::handleException($e);
    echo "<p class='text-danger'>Error: " . Helper::getDatabaseErrorMessage('fetch', 'authors') . "</p>";
}

// Example 2: Get a record by ID
echo "<h3>Example 2: Get author by ID</h3>";
try {
    $authorId = 1; // Replace with an actual author ID
    $author = $dbOps->getById('authors', $authorId);
    echo "<pre>";
    print_r($author);
    echo "</pre>";
} catch (Exception $e) {
    Helper::handleException($e);
    echo "<p class='text-danger'>Error: " . Helper::getDatabaseErrorMessage('fetch', 'author') . "</p>";
}

// Example 3: Insert a new record
echo "<h3>Example 3: Insert a new author</h3>";
try {
    // This is just an example - don't actually run this unless you want to insert a new record
    /*
    $newAuthor = [
        'name' => 'Example Author',
        'biography' => 'This is an example author biography.',
        'book_type' => 'Fiction'
    ];
    $newAuthorId = $dbOps->insert('authors', $newAuthor);
    echo "New author ID: " . $newAuthorId;
    */
    echo "<p>Insert code is commented out to prevent actual database changes.</p>";
} catch (Exception $e) {
    Helper::handleException($e);
    echo "<p class='text-danger'>Error: " . Helper::getDatabaseErrorMessage('create', 'author') . "</p>";
}

// Example 4: Update a record
echo "<h3>Example 4: Update an author</h3>";
try {
    // This is just an example - don't actually run this unless you want to update a record
    /*
    $authorId = 1; // Replace with an actual author ID
    $updateData = [
        'biography' => 'Updated biography text.'
    ];
    $result = $dbOps->update('authors', $updateData, $authorId);
    echo "Update result: " . ($result ? "Success" : "Failed");
    */
    echo "<p>Update code is commented out to prevent actual database changes.</p>";
} catch (Exception $e) {
    Helper::handleException($e);
    echo "<p class='text-danger'>Error: " . Helper::getDatabaseErrorMessage('update', 'author') . "</p>";
}

// Example 5: Delete a record
echo "<h3>Example 5: Delete an author</h3>";
try {
    // This is just an example - don't actually run this unless you want to delete a record
    /*
    $authorId = 1; // Replace with an actual author ID
    $result = $dbOps->delete('authors', $authorId);
    echo "Delete result: " . ($result ? "Success" : "Failed");
    */
    echo "<p>Delete code is commented out to prevent actual database changes.</p>";
} catch (Exception $e) {
    Helper::handleException($e);
    echo "<p class='text-danger'>Error: " . Helper::getDatabaseErrorMessage('delete', 'author') . "</p>";
}

// Example 6: Search records
echo "<h3>Example 6: Search authors</h3>";
try {
    $searchTerm = 'fiction'; // Example search term
    $searchFields = ['name', 'biography', 'book_type'];
    $results = $dbOps->search('authors', $searchFields, $searchTerm);
    echo "<pre>";
    print_r($results);
    echo "</pre>";
} catch (Exception $e) {
    Helper::handleException($e);
    echo "<p class='text-danger'>Error: " . Helper::getDatabaseErrorMessage('search', 'authors') . "</p>";
}

// Example 7: Count records
echo "<h3>Example 7: Count authors</h3>";
try {
    $count = $dbOps->count('authors');
    echo "Total authors: " . $count;
    
    // Count with condition
    $fictionCount = $dbOps->count('authors', 'book_type = ?', 's', ['Fiction']);
    echo "<br>Fiction authors: " . $fictionCount;
} catch (Exception $e) {
    Helper::handleException($e);
    echo "<p class='text-danger'>Error: " . Helper::getDatabaseErrorMessage('fetch', 'authors') . "</p>";
}

// Example 8: Custom query
echo "<h3>Example 8: Custom query</h3>";
try {
    $sql = "SELECT a.name, COUNT(b.id) as book_count 
            FROM authors a 
            LEFT JOIN books b ON a.id = b.author_id 
            GROUP BY a.id 
            ORDER BY book_count DESC 
            LIMIT 5";
    
    $topAuthors = $dbOps->executeQuery($sql);
    echo "<pre>";
    print_r($topAuthors);
    echo "</pre>";
} catch (Exception $e) {
    Helper::handleException($e);
    echo "<p class='text-danger'>Error executing custom query.</p>";
}