# Library Management System (LMS)

A comprehensive system for managing library books, borrowing, and online purchases.

## Features

- User registration and authentication
- Book browsing and searching
- Book borrowing and return management
- Shopping cart and checkout functionality
- Admin panel for book and user management
- Author management
- Responsive design for mobile and desktop

## Live Demo

Visit [https://github.com/oyaon/LMSG](https://github.com/oyaon/LMSG) to see the project repository.

## Migration Guide

This guide provides instructions for migrating the existing LMS application to the new architecture.

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache web server
- Composer (optional, for future dependencies)

### Backup Step

Before starting the migration, create a backup of your database and code:

```bash
# Backup database
mysqldump -u root -p bms > bms_backup.sql

# Backup code
xcopy /E /I /H /Y "c:\xampp\htdocs\LMS\LMS" "c:\xampp\htdocs\LMS\LMS_backup"
```

### Database Migration Step

1. Create the necessary directories:

```bash
mkdir -p c:\xampp\htdocs\LMS\LMS\database
mkdir -p c:\xampp\htdocs\LMS\LMS\config
mkdir -p c:\xampp\htdocs\LMS\LMS\includes
mkdir -p c:\xampp\htdocs\LMS\LMS\uploads\covers
mkdir -p c:\xampp\htdocs\LMS\LMS\uploads\pdfs
mkdir -p c:\xampp\htdocs\LMS\LMS\uploads\authors
```

2. Run the database migration script:

```bash
cd c:\xampp\htdocs\LMS\LMS
php database/migrate.php
```

3. Verify the migration was successful by checking the migration log:

```bash
type c:\xampp\htdocs\LMS\LMS\database\migration.log
```

### Code Refactoring Step

1. Update each page to use the new class structure. Start with the login and registration pages:

```bash
# Replace old login page with new one
copy c:\xampp\htdocs\LMS\LMS\login.php c:\xampp\htdocs\LMS\LMS\login_page.php.bak
copy c:\xampp\htdocs\LMS\LMS\login_submit.php c:\xampp\htdocs\LMS\LMS\login_submit.php.bak

# Replace old registration page with new one
copy c:\xampp\htdocs\LMS\LMS\registration_page.php c:\xampp\htdocs\LMS\LMS\registration_page.php.bak
copy c:\xampp\htdocs\LMS\LMS\registration_submit.php c:\xampp\htdocs\LMS\LMS\registration_submit.php.bak
```

2. Continue refactoring other pages following the same pattern:

   - Book management pages
   - Borrowing system pages
   - Cart and payment pages
   - Admin pages

### Testing Step

Test each functionality after refactoring to ensure everything works as expected:

1. User registration and login
2. Book browsing and searching
3. Book borrowing
4. Shopping cart and checkout
5. Admin functions

### Deployment Step

Once testing is complete, deploy the updated application to production.

## File Structure

```
LMS/
├── admin/              # Admin pages
├── config/             # Configuration files
├── css/                # CSS files
├── database/           # Database migration scripts
├── images/             # Image assets
├── includes/           # PHP classes and utilities
├── pdfs/               # PDF files (legacy)
├── uploads/            # Uploaded files
│   ├── authors/        # Author images
│   ├── covers/         # Book cover images
│   └── pdfs/           # PDF files
├── index.php           # Home page
├── login.php           # Login page
├── register.php        # Registration page
├── book-details.php    # Book details page
├── all-books.php       # All books page
├── borrow.php          # Borrow page
├── cart-page.php       # Cart page
├── payment.php         # Payment page
├── README.md           # Documentation
└── ROADMAP.md          # Migration roadmap
```

## Class Structure

- **Database**: Database connection and query methods
- **User**: User authentication and management
- **Book**: Book management
- **Borrow**: Book borrowing operations
- **Cart**: Shopping cart and payment operations
- **Helper**: Utility functions

## Usage Examples

### User Authentication

```php
// Include initialization file
require_once 'includes/init.php';

// Authenticate user
if ($user->authenticate($email, $password)) {
    // Redirect based on user type
    if ($user->isAdmin()) {
        Helper::redirect('admin/index.php');
    } else {
        Helper::redirect('index.php');
    }
} else {
    $errors[] = 'Invalid email or password';
}
```

### Book Management

```php
// Include initialization file
require_once 'includes/init.php';

// Get all books
$allBooks = $book->getAllBooks();

// Get book by ID
$bookDetails = $book->getBookById($id);

// Search books
$searchResults = $book->searchBooks($query, $category);
```

### Borrowing System

```php
// Include initialization file
require_once 'includes/init.php';

// Request to borrow a book
$result = $borrow->requestBorrow($userEmail, $bookId);

// Get user's borrow history
$borrowHistory = $borrow->getUserBorrowHistory($userEmail);
```

### Shopping Cart

```php
// Include initialization file
require_once 'includes/init.php';

// Add book to cart
$result = $cart->addToCart($userEmail, $bookId);

// Get cart items
$cartItems = $cart->getCartItems($userEmail);

// Process payment
$result = $cart->processPayment($userEmail, $bookIds, $amount, $transactionId);
```

## Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature-name`
3. Commit your changes: `git commit -m 'Add some feature'`
4. Push to the branch: `git push origin feature-name`
5. Submit a pull request

## License

This project is licensed under the MIT License.