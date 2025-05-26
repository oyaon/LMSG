# Library Management System (LMS)

A comprehensive system for managing library books, borrowing, and online purchases.

## Table of Contents

- [Features](#features)
- [Project Repository](#project-repository)
- [Setup Instructions (Windows)](#setup-instructions-windows)
- [Usage Examples](#usage-examples)
- [Contributing](#contributing)
- [License](#license)

## Features

The Library Management System (LMS) offers a comprehensive set of features designed to facilitate efficient library operations and enhance user experience:

- **User Management:** Secure user registration, authentication, and role-based access control to differentiate between regular users and administrators.
- **Book Management:** Browse, search, add, edit, and delete books with detailed metadata including authors, categories, and availability status.
- **Borrowing System:** Request, approve, and manage book borrowings with tracking of due dates and return status.
- **Shopping Cart and Checkout:** Add books to a shopping cart and complete purchases through an integrated checkout process.
- **Admin Panel:** Manage users, books, authors, borrowing requests, donations, and special offers through a dedicated administrative interface.
- **Notification System:** Receive alerts and notifications for due dates, new arrivals, special offers, and account activities.
- **Donation Management:** Handle book and monetary donations with tracking and acknowledgment features.
- **Special Offers and Exclusive Content:** Promote special deals and provide exclusive content to users.
- **Responsive Design:** Optimized for both desktop and mobile devices to ensure accessibility and usability.
- **Laravel Framework Integration:** Utilizes Laravel for enhanced security, maintainability, and testability of the backend.

## Current Project Status

The project is progressing through multiple phases of development. Below is a summary of the current status:

### Completed Phases

- **Phase 1 - Database Migration**
  - Database schema creation, migration scripts, backup and restore functionality, and documentation are complete.
- **Phase 2 - Backend Restructuring**
  - Configuration system, database connection, user and book management, borrowing system, cart and payment classes, helper utilities, error handling, and logging system are implemented.
- **Phase 3 - Security Enhancements**
  - Password hashing, CSRF protection, input validation, role-based access control, secure file uploads, session security, and rate limiting are implemented.
- **Phase 7 - Documentation and Training**
  - User, administrator, and developer documentation are created.

### Pending Phases

- **Phase 4 - Frontend Improvements**
  - Responsive design improvements, reusable UI components, enhanced user experience, client-side validation, loading indicators, improved error messages, and accessibility enhancements.
- **Phase 5 - Feature Enhancements**
  - Advanced search, user profile management, email notifications, book ratings and reviews, reporting system, book recommendations, and admin dashboard enhancements.
- **Phase 6 - Testing and Deployment**
  - Comprehensive test suite, security testing, user acceptance testing, deployment plan, rollback strategy, and deployment documentation.

## API Documentation

The LMS exposes several API endpoints to interact with the system programmatically. Below are some of the primary endpoints:

- **User Authentication**
  - `POST /api/login` - Authenticate a user with email and password.
  - `POST /api/register` - Register a new user account.
  - `POST /api/logout` - Log out the authenticated user.

- **Books**
  - `GET /api/books` - Retrieve a paginated list of books.
  - `GET /api/books/{id}` - Retrieve details of a specific book.
  - `POST /api/books` - Add a new book (admin only).
  - `PUT /api/books/{id}` - Update book details (admin only).
  - `DELETE /api/books/{id}` - Delete a book (admin only).

- **Borrowing**
  - `POST /api/borrow` - Request to borrow a book.
  - `GET /api/borrow/history` - Get borrowing history for the user.
  - `PUT /api/borrow/{id}/return` - Mark a borrowed book as returned.

- **Shopping Cart**
  - `GET /api/cart` - Get current user's shopping cart.
  - `POST /api/cart/add` - Add a book to the cart.
  - `POST /api/cart/checkout` - Complete the purchase.

### Request and Response Formats

- All requests and responses use JSON format.
- Authentication tokens are passed via HTTP headers.
- Standard HTTP status codes are used to indicate success or failure.

## Developer Setup

To set up the development environment for LMS, follow these steps:

1. Ensure you have PHP, Composer, Node.js, and npm installed on your system.
2. Clone the repository:

   ```bash
   git clone https://github.com/oyaon/LMSG.git
   cd LMSG
   ```

3. Install PHP dependencies:

   ```bash
   composer install
   ```

4. Configure your database settings in `config/config.php`.
5. Import the database schema and seed data using phpMyAdmin or command line.
6. For Laravel components, navigate to the `lms-laravel` directory and install Node.js dependencies:

   ```bash
   cd lms-laravel
   npm install
   npm run build
   ```

7. Generate Laravel application key and run migrations:

   ```bash
   php artisan key:generate
   php artisan migrate --seed
   php artisan storage:link
   ```

8. Set appropriate permissions for `uploads/` and `storage/` directories.
9. Start your local development server (e.g., using XAMPP Apache).
10. Access the application at `http://localhost/LMS`.

## Testing Instructions

The LMS includes various tests to ensure functionality and stability:

- **Unit Tests:** Located in the `tests/` directory, covering core classes and utilities.
- **Feature Tests:** Cover user interactions, API endpoints, and workflows.
- **Running Tests:** Use PHPUnit to run tests:

  ```bash
  ./vendor/bin/phpunit
  ```

- **Test Coverage:** Aim to cover critical paths including authentication, book management, borrowing, and shopping cart.
- **Manual Testing:** Verify UI components, forms, and user flows in the browser.
- **Error Logs:** Check logs in the `logs/` directory for any issues during testing.

## Usage Examples

### User Authentication

```php
if (Auth::attempt(['email' => $email, 'password' => $password])) {
    // Authentication passed
    return redirect()->intended('dashboard');
} else {
    // Authentication failed
    return back()->withErrors(['email' => 'Invalid credentials']);
}
```

### Book Management

```php
$books = Book::paginate(12);
$books = Book::where('name', 'like', "%$query%")
    ->orWhere('author', 'like', "%$query%")
    ->paginate(12);
```

### Borrowing System

```php
BorrowController::requestBorrow($bookId, $userId);
```

### Shopping Cart

```php
CartController::add($bookId, $userEmail);
```

## File Structure

```plaintext
LMS/
├── admin/              # Admin pages
├── config/             # Configuration files
├── css/                # CSS files
├── database/           # Laravel migrations and seeders
├── images/             # Image assets
├── includes/           # PHP classes and utilities
├── uploads/            # Uploaded files (authors, covers, pdfs)
├── resources/          # Laravel views and assets
├── routes/             # Laravel route definitions
├── app/                # Laravel application code (Controllers, Models)
├── public/             # Public web root
├── README.md           # Project documentation
├── lms-laravel/laravel-migrate-setup.ps1 # Migration automation script
└── ...
```

## Detailed Instructions

### Setup

- Ensure XAMPP is installed and Apache/MySQL services are running.
- Import the database using phpMyAdmin or command line from the `database/` folder.
- Configure database credentials in `config/config.php` to match your environment.
- For Laravel components, navigate to `lms-laravel` and run the composer and npm commands as described in Setup Instructions.
- Set proper file permissions for `uploads/` and `storage/` directories to allow file uploads and storage.

### Running the Application

- Access the application via `http://localhost/LMS` in your browser.
- Use the registration page to create a new user account.
- Admin users can manage books, authors, and users via the admin panel.
- Use the search and browse features to find books.
- Borrow books and manage your borrowing history through your user profile.
- Use the shopping cart and checkout for purchasing books online.

## Troubleshooting Tips

- **Database Connection Errors:** Verify database credentials in `config/config.php` and ensure MySQL service is running.
- **File Upload Issues:** Check directory permissions for `uploads/` and ensure PHP file upload limits are sufficient.
- **Laravel Migration Failures:** Ensure `.env` file is configured correctly and run `php artisan migrate --seed` again.
- **Authentication Problems:** Clear browser cookies and sessions, and verify password hashing settings in `includes/User.php`.
- **Missing Assets or CSS Issues:** Run `npm run build` in `lms-laravel` and clear browser cache.
- **Error Logs:** Check PHP error logs and application logs in `logs/` directory for detailed error messages.

## Contributing

Contributions are welcome! Please follow these steps:

This project is licensed under the MIT License. See the LICENSE file for details.

## Last Updated

May 30, 2025
