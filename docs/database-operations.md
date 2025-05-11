# Database Operations and Error Handling

This document provides guidelines on how to use the database operations and error handling features in the LMS application.

## Database Operations

The LMS application provides two levels of database operations:

1. **Generic Database Operations**: The `DatabaseOperations` class provides reusable functions for common database operations across any table.

2. **Entity-Specific Operations**: Classes like `BookOperations` and `Author` provide specialized functions for specific entities.

### Basic Usage

```php
// Include initialization file
require_once 'includes/init.php';

// The $dbOps object is already initialized in init.php
// You can use it directly in your code
```

### Available Methods

#### Get All Records Method

```php
// Get all authors, ordered by name
$authors = $dbOps->getAll('authors', 'name', 'ASC');

// Get 10 most recent books
$recentBooks = $dbOps->getAll('books', 'created_at', 'DESC', 10, 0);
```

#### Get Record by ID Method

```php
// Get author with ID 1
$author = $dbOps->getById('authors', 1);
```

#### Get Records by Field Method

```php
// Get all books by a specific author
$books = $dbOps->getByField('books', 'author_id', 1, 'i');

// Get all users with a specific role
$users = $dbOps->getByField('users', 'role', 'admin', 's');
```

#### Insert Record Method

```php
// Insert a new author
$newAuthor = [
    'name' => 'John Doe',
    'biography' => 'A prolific writer...',
    'book_type' => 'Fiction'
];
$newAuthorId = $dbOps->insert('authors', $newAuthor);
```

#### Update Record Method

```php
// Update an author
$updateData = [
    'biography' => 'Updated biography text.'
];
$result = $dbOps->update('authors', $updateData, 1);
```

#### Delete Record Method

```php
// Delete an author
$result = $dbOps->delete('authors', 1);
```

#### Count Records Method

```php
// Count all authors
$totalAuthors = $dbOps->count('authors');

// Count fiction authors
$fictionAuthors = $dbOps->count('authors', 'book_type = ?', 's', ['Fiction']);
```

#### Search Records Method

```php
// Search for authors
$searchTerm = 'fiction';
$searchFields = ['name', 'biography', 'book_type'];
$results = $dbOps->search('authors', $searchFields, $searchTerm);
```

#### Custom Queries Method

```php
// Execute a custom query
$sql = "SELECT a.name, COUNT(b.id) as book_count 
        FROM authors a 
        LEFT JOIN books b ON a.id = b.author_id 
        GROUP BY a.id 
        ORDER BY book_count DESC 
        LIMIT 5";
$topAuthors = $dbOps->executeQuery($sql);
```

## Error Handling

The application includes a robust error handling system that logs errors and provides user-friendly messages.

### Basic Error Handling

```php
try {
    // Database operation that might fail
    $authors = $dbOps->getAll('authors');
} catch (Exception $e) {
    // Log the error and set a flash message
    Helper::handleException($e);
    
    // Display a user-friendly error message
    echo Helper::getDatabaseErrorMessage('fetch', 'authors');
}
```

### Helper Methods for Error Handling

#### Log Error

```php
Helper::logError("Custom error message", __FILE__, __LINE__);
```

#### Handle Exception

```php
try {
    // Code that might throw an exception
} catch (Exception $e) {
    Helper::handleException($e, true, "Custom user message");
}
```

#### Get Database Error Message

```php
// Get a user-friendly error message for a database operation
$message = Helper::getDatabaseErrorMessage('update', 'book');
```

### Flash Messages

Flash messages are temporary messages displayed to the user after a redirect.

```php
// Set a flash message
Helper::setFlashMessage('success', 'Author added successfully!');

// Redirect to another page
header('Location: authors.php');
exit;
```

In the target page, display the flash message:

```php
// Display flash message if any
Helper::displayFlashMessage();
```

## Best Practices

1. **Always use try-catch blocks** for database operations to handle errors gracefully.
2. **Use prepared statements** to prevent SQL injection (the DatabaseOperations class does this automatically).
3. **Provide user-friendly error messages** instead of exposing technical details.
4. **Log detailed error information** for debugging purposes.
5. **Validate input data** before performing database operations.
6. **Use transactions** for operations that involve multiple database changes.

## Entity-Specific Operations

The application includes specialized classes for entity-specific database operations.

### BookOperations Class

The `BookOperations` class provides methods for book-related database operations.

#### Basic Usage for BookOperations

```php
// Include initialization file
require_once 'includes/init.php';

// The $bookOps object is already initialized in init.php
// You can use it directly in your code
```

#### Available BookOperations Methods

##### Get All Books Method

```php
// Get all books
$books = $bookOps->getAllBooks();

// Get books with filters
$books = $bookOps->getAllBooks(['category' => 'Fiction'], 'name', 'ASC', 10, 0);
```

##### Get Book by ID Method

```php
// Get book with ID 1
$book = $bookOps->getBookById(1);
```

##### Get Books by Category Method

```php
// Get fiction books
$books = $bookOps->getBooksByCategory('Fiction', 10);
```

##### Search Books Method

```php
// Search for books
$books = $bookOps->searchBooks('programming', 'Technology');
```

##### Get Latest Books Method

```php
// Get 5 latest books
$books = $bookOps->getLatestBooks(5);
```

##### Add a New Book Method

```php
// Add a new book
$bookData = [
    'name' => 'Example Book',
    'author' => 'Example Author',
    'category' => 'Fiction',
    'description' => 'This is an example book description.',
    'quantity' => 10,
    'price' => 19.99
];
$newBookId = $bookOps->addBook($bookData);
```

##### Update a Book Method

```php
// Update a book
$bookData = [
    'name' => 'Updated Book Title',
    'author' => 'Updated Author',
    'category' => 'Fiction',
    'description' => 'This is an updated book description.',
    'quantity' => 15,
    'price' => 24.99
];
$result = $bookOps->updateBook(1, $bookData);
```

##### Delete a Book Method

```php
// Delete a book
$result = $bookOps->deleteBook(1);
```

### Author Class

The `Author` class provides methods for author-related database operations.

#### Basic Usage for Author Class

```php
// Include initialization file
require_once 'includes/init.php';

// The $author object is already initialized in init.php
// You can use it directly in your code
```

#### Available Author Methods

##### Get All Authors Method

```php
// Get all authors
$authors = $author->getAllAuthors();
```

##### Get Author by ID Method

```php
// Get author with ID 1
$authorData = $author->getAuthorById(1);
```

##### Add a New Author Method

```php
// Add a new author
$newAuthorId = $author->addAuthor('John Doe', 'A prolific writer...', 'Fiction');
```

##### Update an Author Method

```php
// Update an author
$result = $author->updateAuthor(1, 'John Doe', 'Updated biography...', 'Fiction');
```

##### Delete an Author Method

```php
// Delete an author
$result = $author->deleteAuthor(1);
```

##### Search Authors Method

```php
// Search for authors
$authors = $author->searchAuthors('fiction');
```

## Example Implementations

See the following examples for complete demonstrations:

- `examples/database-operations-example.php` - Generic database operations
- `examples/book-operations-example.php` - Book-specific operations