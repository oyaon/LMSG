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

- User registration and authentication
- Book browsing and searching
- Book borrowing and return management
- Shopping cart and checkout functionality
- Admin panel for book and user management
- Author management
- Responsive design for mobile and desktop
- Laravel framework integration for improved security, maintainability, and testability
- Notification system for users
- Donation management (books and money)
- Special offers and exclusive content

## Project Repository

Visit the [LMS GitHub Repository](https://github.com/oyaon/LMSG) to see the full project source code.

## Setup Instructions (Windows)

1. Clone the repository:

   ```powershell
   git clone https://github.com/oyaon/LMSG.git
   ```

2. Install XAMPP and start Apache/MySQL.
3. Import the database from `database/` folder using phpMyAdmin.
4. Copy the project files to your XAMPP `htdocs` directory.
5. Configure database settings in `config/config.php`.
6. (Laravel) Navigate to `lms-laravel` and run:

   ```powershell
   composer install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate --seed
   php artisan storage:link
   npm install && npm run build
   ```

7. Access the app at `http://localhost/LMS`.

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

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Commit your changes with clear and concise messages.
4. Push your branch to your forked repository.
5. Submit a pull request to the main repository.

## License

This project is licensed under the MIT License. See the LICENSE file for details.

## Last Updated

May 23, 2025
