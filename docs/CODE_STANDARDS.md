# Library Management System - Code Standards

This document outlines the coding standards and best practices for the Library Management System (LMS) project. Following these standards ensures consistency, maintainability, and quality across the codebase.

## Table of Contents

1. [PHP Coding Standards](#php-coding-standards)
2. [HTML/CSS Standards](#htmlcss-standards)
3. [JavaScript Standards](#javascript-standards)
4. [Database Standards](#database-standards)
5. [Documentation Standards](#documentation-standards)
6. [Version Control Standards](#version-control-standards)
7. [Security Best Practices](#security-best-practices)
8. [Testing Standards](#testing-standards)

## PHP Coding Standards

### File Format
- Use UTF-8 encoding without BOM
- Use LF (Unix) line endings
- End each file with a single blank line

### Naming Conventions
- **Classes**: Use PascalCase (e.g., `BookController`, `UserAuthentication`)
- **Methods/Functions**: Use camelCase (e.g., `getUserById()`, `processPayment()`)
- **Variables**: Use camelCase (e.g., `$userName`, `$bookCount`)
- **Constants**: Use UPPER_CASE with underscores (e.g., `MAX_LOGIN_ATTEMPTS`, `DEFAULT_TIMEOUT`)
- **Database Tables**: Use snake_case (e.g., `book_categories`, `user_profiles`)

### Code Structure
- One class per file
- Namespace declarations should be on the first line
- Import statements should follow namespace declaration
- Class opening brace on the same line as the class name
- Method opening brace on the same line as the method name
- One blank line between methods

### Example Class Structure
```php
<?php
namespace LMS\Controllers;

use LMS\Models\Book;
use LMS\Services\LogService;

class BookController
{
    private $logService;
    
    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }
    
    public function getBookById($id)
    {
        // Implementation
    }
    
    public function updateBook($id, $data)
    {
        // Implementation
    }
}
```

### Indentation and Formatting
- Use 4 spaces for indentation (not tabs)
- Maximum line length of 80-120 characters
- Operators should have spaces around them
- Commas should have a space after them
- No trailing whitespace

### Comments
- Use DocBlocks for classes and methods
- Use single-line comments (`//`) for inline explanations
- Comment complex logic and business rules

### Error Handling
- Use try-catch blocks for exception handling
- Log exceptions and errors
- Return meaningful error messages to users

## HTML/CSS Standards

### HTML
- Use HTML5 doctype
- Use lowercase element names and attributes
- Quote attribute values
- Use semantic HTML elements
- Validate HTML against W3C standards

### CSS
- Use consistent class naming convention (BEM recommended)
- Group related styles together
- Use CSS variables for colors and repeated values
- Minimize use of !important
- Use media queries for responsive design

### Example HTML/CSS
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header class="site-header">
        <h1 class="site-title">Library Management System</h1>
    </header>
    <main class="content">
        <article class="book-details">
            <h2 class="book-details__title">Book Title</h2>
            <p class="book-details__author">Author Name</p>
        </article>
    </main>
</body>
</html>
```

## JavaScript Standards

### Naming Conventions
- Use camelCase for variables and functions
- Use PascalCase for constructor functions and classes
- Use UPPER_CASE for constants

### Code Structure
- Use strict mode (`'use strict';`)
- Declare variables at the top of their scope
- Use ES6 features when appropriate
- Avoid global variables

### Example JavaScript
```javascript
'use strict';

class BookManager {
    constructor() {
        this.books = [];
    }
    
    addBook(book) {
        this.books.push(book);
    }
    
    findBookById(id) {
        return this.books.find(book => book.id === id);
    }
}

// Usage
const manager = new BookManager();
manager.addBook({ id: 1, title: 'JavaScript Basics' });
```

## Database Standards

### Table Naming
- Use plural, lowercase names with underscores (e.g., `books`, `user_profiles`)
- Use consistent prefixes for related tables (e.g., `book_categories`, `book_authors`)

### Column Naming
- Use singular, lowercase names with underscores (e.g., `first_name`, `created_at`)
- Use `id` for primary keys
- Use `{table_name}_id` for foreign keys (e.g., `book_id`, `user_id`)

### Data Types
- Use appropriate data types for columns
- Use `VARCHAR` with specified length for strings
- Use `TEXT` for long text content
- Use `DATETIME` or `TIMESTAMP` for dates and times
- Use `DECIMAL` for currency values

### Indexes
- Add indexes to frequently queried columns
- Add indexes to foreign key columns
- Name indexes consistently (e.g., `idx_{table}_{column}`)

### Example SQL
```sql
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author_id INT NOT NULL,
    isbn VARCHAR(20) UNIQUE,
    published_date DATE,
    price DECIMAL(10, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_books_author_id (author_id),
    FOREIGN KEY (author_id) REFERENCES authors(id)
);
```

## Documentation Standards

### Code Documentation
- Use DocBlocks for classes, methods, and functions
- Document parameters, return values, and exceptions
- Explain complex logic and business rules

### Project Documentation
- Keep README.md up to date
- Document system architecture
- Document API endpoints
- Provide setup and installation instructions
- Include troubleshooting guides

### Example DocBlock
```php
/**
 * Processes a book borrowing request
 *
 * @param int $userId The ID of the user making the request
 * @param int $bookId The ID of the book to borrow
 * @param string $dueDate The expected return date (YYYY-MM-DD)
 * @return array The borrowing record details
 * @throws BookNotFoundException If the book doesn't exist
 * @throws BookUnavailableException If the book is already borrowed
 */
public function processBorrowRequest($userId, $bookId, $dueDate)
{
    // Implementation
}
```

## Version Control Standards

### Branching Strategy
- `main` branch for production code
- `develop` branch for development
- Feature branches for new features
- Hotfix branches for urgent fixes

### Commit Messages
- Use present tense ("Add feature" not "Added feature")
- First line is a summary (max 50 characters)
- Followed by a blank line and detailed description
- Reference issue numbers when applicable

### Example Commit Message
```
Add book borrowing functionality

- Implement borrowing request processing
- Add due date calculation
- Create email notification for borrowers
- Add unit tests for borrowing logic

Fixes #123
```

### Pull Requests
- Provide a clear description of changes
- Reference related issues
- Ensure all tests pass
- Request review from appropriate team members

## Security Best Practices

### Authentication
- Use password hashing (bcrypt)
- Implement account lockout after failed attempts
- Use HTTPS for all communications
- Implement proper session management

### Input Validation
- Validate all user inputs
- Use prepared statements for database queries
- Sanitize outputs to prevent XSS

### Access Control
- Implement role-based access control
- Verify permissions for all actions
- Log security-related events

### Example Security Code
```php
// Password hashing
$hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

// Prepared statement
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

// Output sanitization
echo htmlspecialchars($userName, ENT_QUOTES, 'UTF-8');
```

## Testing Standards

### Unit Testing
- Write tests for all business logic
- Use PHPUnit for PHP code testing
- Aim for high code coverage
- Test edge cases and error conditions

### Integration Testing
- Test interactions between components
- Test database operations
- Test API endpoints

### Example Test
```php
public function testBookBorrowing()
{
    $user = $this->createTestUser();
    $book = $this->createTestBook();
    
    $result = $this->borrowService->processBorrowRequest(
        $user->id,
        $book->id,
        '2023-12-31'
    );
    
    $this->assertTrue($result['success']);
    $this->assertEquals($user->id, $result['borrow_record']['user_id']);
    $this->assertEquals($book->id, $result['borrow_record']['book_id']);
}
```

## Conclusion

Following these coding standards will ensure that the Library Management System codebase remains consistent, maintainable, and of high quality. All team members should adhere to these standards when contributing to the project.

For questions or suggestions regarding these standards, please contact the project maintainers.# Library Management System - Code Standards

This document outlines the coding standards and best practices for the Library Management System (LMS) project. Following these standards ensures consistency, maintainability, and quality across the codebase.

## Table of Contents

1. [PHP Coding Standards](#php-coding-standards)
2. [HTML/CSS Standards](#htmlcss-standards)
3. [JavaScript Standards](#javascript-standards)
4. [Database Standards](#database-standards)
5. [Documentation Standards](#documentation-standards)
6. [Version Control Standards](#version-control-standards)
7. [Security Best Practices](#security-best-practices)
8. [Testing Standards](#testing-standards)

## PHP Coding Standards

### File Format
- Use UTF-8 encoding without BOM
- Use LF (Unix) line endings
- End each file with a single blank line

### Naming Conventions
- **Classes**: Use PascalCase (e.g., `BookController`, `UserAuthentication`)
- **Methods/Functions**: Use camelCase (e.g., `getUserById()`, `processPayment()`)
- **Variables**: Use camelCase (e.g., `$userName`, `$bookCount`)
- **Constants**: Use UPPER_CASE with underscores (e.g., `MAX_LOGIN_ATTEMPTS`, `DEFAULT_TIMEOUT`)
- **Database Tables**: Use snake_case (e.g., `book_categories`, `user_profiles`)

### Code Structure
- One class per file
- Namespace declarations should be on the first line
- Import statements should follow namespace declaration
- Class opening brace on the same line as the class name
- Method opening brace on the same line as the method name
- One blank line between methods

### Example Class Structure
```php
<?php
namespace LMS\Controllers;

use LMS\Models\Book;
use LMS\Services\LogService;

class BookController
{
    private $logService;
    
    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }
    
    public function getBookById($id)
    {
        // Implementation
    }
    
    public function updateBook($id, $data)
    {
        // Implementation
    }
}
```

### Indentation and Formatting
- Use 4 spaces for indentation (not tabs)
- Maximum line length of 80-120 characters
- Operators should have spaces around them
- Commas should have a space after them
- No trailing whitespace

### Comments
- Use DocBlocks for classes and methods
- Use single-line comments (`//`) for inline explanations
- Comment complex logic and business rules

### Error Handling
- Use try-catch blocks for exception handling
- Log exceptions and errors
- Return meaningful error messages to users

## HTML/CSS Standards

### HTML
- Use HTML5 doctype
- Use lowercase element names and attributes
- Quote attribute values
- Use semantic HTML elements
- Validate HTML against W3C standards

### CSS
- Use consistent class naming convention (BEM recommended)
- Group related styles together
- Use CSS variables for colors and repeated values
- Minimize use of !important
- Use media queries for responsive design

### Example HTML/CSS
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header class="site-header">
        <h1 class="site-title">Library Management System</h1>
    </header>
    <main class="content">
        <article class="book-details">
            <h2 class="book-details__title">Book Title</h2>
            <p class="book-details__author">Author Name</p>
        </article>
    </main>
</body>
</html>
```

## JavaScript Standards

### Naming Conventions
- Use camelCase for variables and functions
- Use PascalCase for constructor functions and classes
- Use UPPER_CASE for constants

### Code Structure
- Use strict mode (`'use strict';`)
- Declare variables at the top of their scope
- Use ES6 features when appropriate
- Avoid global variables

### Example JavaScript
```javascript
'use strict';

class BookManager {
    constructor() {
        this.books = [];
    }
    
    addBook(book) {
        this.books.push(book);
    }
    
    findBookById(id) {
        return this.books.find(book => book.id === id);
    }
}

// Usage
const manager = new BookManager();
manager.addBook({ id: 1, title: 'JavaScript Basics' });
```

## Database Standards

### Table Naming
- Use plural, lowercase names with underscores (e.g., `books`, `user_profiles`)
- Use consistent prefixes for related tables (e.g., `book_categories`, `book_authors`)

### Column Naming
- Use singular, lowercase names with underscores (e.g., `first_name`, `created_at`)
- Use `id` for primary keys
- Use `{table_name}_id` for foreign keys (e.g., `book_id`, `user_id`)

### Data Types
- Use appropriate data types for columns
- Use `VARCHAR` with specified length for strings
- Use `TEXT` for long text content
- Use `DATETIME` or `TIMESTAMP` for dates and times
- Use `DECIMAL` for currency values

### Indexes
- Add indexes to frequently queried columns
- Add indexes to foreign key columns
- Name indexes consistently (e.g., `idx_{table}_{column}`)

### Example SQL
```sql
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author_id INT NOT NULL,
    isbn VARCHAR(20) UNIQUE,
    published_date DATE,
    price DECIMAL(10, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_books_author_id (author_id),
    FOREIGN KEY (author_id) REFERENCES authors(id)
);
```

## Documentation Standards

### Code Documentation
- Use DocBlocks for classes, methods, and functions
- Document parameters, return values, and exceptions
- Explain complex logic and business rules

### Project Documentation
- Keep README.md up to date
- Document system architecture
- Document API endpoints
- Provide setup and installation instructions
- Include troubleshooting guides

### Example DocBlock
```php
/**
 * Processes a book borrowing request
 *
 * @param int $userId The ID of the user making the request
 * @param int $bookId The ID of the book to borrow
 * @param string $dueDate The expected return date (YYYY-MM-DD)
 * @return array The borrowing record details
 * @throws BookNotFoundException If the book doesn't exist
 * @throws BookUnavailableException If the book is already borrowed
 */
public function processBorrowRequest($userId, $bookId, $dueDate)
{
    // Implementation
}
```

## Version Control Standards

### Branching Strategy
- `main` branch for production code
- `develop` branch for development
- Feature branches for new features
- Hotfix branches for urgent fixes

### Commit Messages
- Use present tense ("Add feature" not "Added feature")
- First line is a summary (max 50 characters)
- Followed by a blank line and detailed description
- Reference issue numbers when applicable

### Example Commit Message
```
Add book borrowing functionality

- Implement borrowing request processing
- Add due date calculation
- Create email notification for borrowers
- Add unit tests for borrowing logic

Fixes #123
```

### Pull Requests
- Provide a clear description of changes
- Reference related issues
- Ensure all tests pass
- Request review from appropriate team members

## Security Best Practices

### Authentication
- Use password hashing (bcrypt)
- Implement account lockout after failed attempts
- Use HTTPS for all communications
- Implement proper session management

### Input Validation
- Validate all user inputs
- Use prepared statements for database queries
- Sanitize outputs to prevent XSS

### Access Control
- Implement role-based access control
- Verify permissions for all actions
- Log security-related events

### Example Security Code
```php
// Password hashing
$hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

// Prepared statement
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

// Output sanitization
echo htmlspecialchars($userName, ENT_QUOTES, 'UTF-8');
```

## Testing Standards

### Unit Testing
- Write tests for all business logic
- Use PHPUnit for PHP code testing
- Aim for high code coverage
- Test edge cases and error conditions

### Integration Testing
- Test interactions between components
- Test database operations
- Test API endpoints

### Example Test
```php
public function testBookBorrowing()
{
    $user = $this->createTestUser();
    $book = $this->createTestBook();
    
    $result = $this->borrowService->processBorrowRequest(
        $user->id,
        $book->id,
        '2023-12-31'
    );
    
    $this->assertTrue($result['success']);
    $this->assertEquals($user->id, $result['borrow_record']['user_id']);
    $this->assertEquals($book->id, $result['borrow_record']['book_id']);
}
```

## Conclusion

Following these coding standards will ensure that the Library Management System codebase remains consistent, maintainable, and of high quality. All team members should adhere to these standards when contributing to the project.

For questions or suggestions regarding these standards, please contact the project maintainers.