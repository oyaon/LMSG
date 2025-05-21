// No code was selected, so a new code snippet will be generated.

// Example of improved code for user authentication
if (Auth::attempt(['email' => request()->input('email'), 'password' => request()->input('password')])) {
    // Authentication passed
    return redirect()->intended('dashboard');
} else {
    // Authentication failed
    return back()->withErrors(['email' => 'Invalid credentials']);
}

// Example of improved code for book management
$books = Book::where(function ($query) {
    $query->where('name', 'like', "%".request()->input('query')."%")
          ->orWhere('author', 'like', "%".request()->input('query')."%");
})->paginate(12);

// Example of improved code for borrowing system
try {
    BorrowController::requestBorrow(request()->input('book_id'), auth()->id());
} catch (\Exception $e) {
    // Handle exception
}

// Example of improved code for shopping cart
try {
    CartController::add(request()->input('book_id'), auth()->user()->email);
} catch (\Exception $e) {
    // Handle exception
}# Library Management System (LMS)

A comprehensive system for managing library books, borrowing, and online purchases.

## Features

- User registration and authentication
- Book browsing and searching
- Book borrowing and return management
- Shopping cart and checkout functionality
- Admin panel for book and user management
- Author management
- Responsive design for mobile and desktop
- Fully migrated to Laravel framework for improved security, maintainability, and testability

## Project Repository

Visit the [LMS GitHub Repository](https://github.com/oyaon/LMSG) to see the full project source code.

## Laravel Migration

The LMS project has been migrated to the Laravel framework. This migration includes:

- Database migrations and seeders for all tables and initial data
- Refactored codebase using Laravel MVC architecture
- Routes defined with authentication and admin middleware
- Automated setup script for dependencies, migrations, and asset building

### Migration Setup

Run the provided PowerShell script [`laravel-migrate-setup.ps1`](lms-laravel/laravel-migrate-setup.ps1) in the `lms-laravel` directory to automate:

- Installing PHP and frontend dependencies
- Generating app key
- Running database migrations and seeders
- Creating storage symbolic link
- Running tests
- Building frontend assets

### Database Migrations

Migration files are located in [`lms-laravel/database/migrations`](lms-laravel/database/migrations) and cover all necessary tables including:

- users, authors, all_books, borrow_history, cart, payments, special_offer, money_donations, book_donations

Foreign keys and indexes are properly defined for data integrity.

## Testing

All critical features have been tested thoroughly, including:

- User authentication and profile management
- Book management and browsing
- Borrowing and cart operations
- Donations and payments
- Admin dashboard and management

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

## Usage Examples

### User Authentication

```php
// Laravel authentication example
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
// Fetch all books with pagination
$books = Book::paginate(12);

// Search books by name or author
$books = Book::where('name', 'like', "%$query%")
    ->orWhere('author', 'like', "%$query%")
    ->paginate(12);
```

### Borrowing System

```php
// Request to borrow a book
BorrowController::requestBorrow($bookId, $userId);
```

### Shopping Cart

```php
// Add book to cart
CartController::add($bookId, $userEmail);
```

## Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature-name`
3. Commit your changes: `git commit -m 'Add some feature'`
4. Push to the branch: `git push origin feature-name`
5. Submit a pull request

## License

This project is licensed under the MIT License.

---

*Thank you for checking out the Library Management System! If you have suggestions or want to contribute, feel free to open an issue or pull request.*
