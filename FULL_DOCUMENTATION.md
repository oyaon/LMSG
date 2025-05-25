# Library Management System (LMS) - Full Documentation

## 1. Project Overview and Features

The Library Management System (LMS) is a comprehensive system for managing library books, borrowing, and online purchases.

### Features

- User registration and authentication
- Book browsing and searching
- Book borrowing and return management
- Shopping cart and checkout functionality
- Admin panel for book and user management
- Author management
- Responsive design for mobile and desktop

## 2. Setup and Installation Instructions

### Prerequisites

- PHP 7.4 or higher (PHP 8.1 recommended for Laravel migration)
- MySQL 5.7 or higher
- Apache web server
- Composer (optional, for Laravel dependencies)
- Node.js and NPM (for frontend assets in Laravel migration)

### Installation Steps

#### Classic LMS Setup

1. Backup your database and code:

   ```bash
   mysqldump -u root -p bms > bms_backup.sql
   xcopy /E /I /H /Y "c:\xampp\htdocs\LMS\LMS" "c:\xampp\htdocs\LMS\LMS_backup"
   ```

2. Create necessary directories:

   ```bash
   mkdir -p c:\xampp\htdocs\LMS\LMS\database
   mkdir -p c:\xampp\htdocs\LMS\LMS\config
   mkdir -p c:\xampp\htdocs\LMS\LMS\includes
   mkdir -p c:\xampp\htdocs\LMS\LMS\uploads\covers
   mkdir -p c:\xampp\htdocs\LMS\LMS\uploads\pdfs
   mkdir -p c:\xampp\htdocs\LMS\LMS\uploads\authors
   ```

3. Run the database migration script:

   ```bash
   cd c:\xampp\htdocs\LMS\LMS
   php database/migrate.php
   ```

4. Verify migration success by checking the migration log:

   ```bash
   type c:\xampp\htdocs\LMS\LMS\database\migration.log
   ```

5. Update pages to use the new class structure (login, registration, book management, borrowing, cart, payment, admin).

6. Test all functionalities.

7. Deploy to production.

#### Laravel Migration Setup

1. Clone the Laravel repository:

   ```bash
   git clone https://github.com/yourusername/laravel-lms.git
   cd laravel-lms
   ```

2. Install PHP dependencies:

   ```bash
   composer install
   ```

3. Install frontend dependencies:

   ```bash
   npm install
   npm run dev
   ```

4. Copy `.env.example` to `.env` and configure database settings.

   ```bash
   cp .env.example .env
   ```

5. Generate application key:

   ```bash
   php artisan key:generate
   ```

6. Run migrations:

   ```bash
   php artisan migrate
   ```

7. Seed database (optional):

   ```bash
   php artisan db:seed
   ```

8. Create symbolic link for storage:

   ```bash
   php artisan storage:link
   ```

9. Start development server:

   ```bash
   php artisan serve
   ```

## 3. Database Schema and Relationships

### Tables

1. **Users**:
   - Stores user information with hashed passwords and user types (Admin or Regular User).
   - Columns: `id`, `first_name`, `last_name`, `user_name`, `email`, `password`, `user_type`, `created_at`, `updated_at`.

2. **Authors**:
   - Contains author details, including optional biography and image.
   - Columns: `id`, `name`, `biography`, `image`, `created_at`, `updated_at`.

3. **Books**:
   - Includes book details, linking to authors via `author_id`.
   - Columns: `id`, `name`, `author_id`, `author`, `category`, `description`, `quantity`, `price`, `pdf`, `cover_image`, `created_at`, `updated_at`.

4. **Borrow History**:
   - Tracks borrowing records with statuses like "Requested", "Issued", "Returned", and "Declined".
   - Columns: `id`, `user_email`, `book_id`, `issue_date`, `fine`, `status`.

5. **Cart**:
   - Manages books in user carts, with a status indicating purchase.
   - Columns: `id`, `user_id`, `book_id`, `quantity`, `status`.

6. **Payments**:
   - Records payment details, including book IDs and transaction status.
   - Columns: `id`, `user_id`, `amount`, `transaction_id`, `status`, `created_at`.

### Relationships

- `author_id` in `all_books` references `authors`.
- `book_id` in `borrow_history` and `cart` references `all_books`.
- Foreign keys ensure data integrity and support cascading updates/deletes.

## 4. Refactored Code and Security Enhancements

### Refactored Files

1. **actions.php**:
   - Replaced raw SQL queries with prepared statements to prevent SQL injection.
   - Improved readability with a `switch-case` structure.

### Security Enhancements

- Implemented CSRF tokens in forms (e.g., `register.html`).
- Used prepared statements in `migrate.php` and `actions.php` for secure database interactions.
- Validated and sanitized user inputs across the application.

## 5. Testing and Validation

### Test Cases

- Found in `tests/` and `lms-laravel/tests/`.
- Includes PHPUnit and Laravel Pest tests for user authentication, book management, and borrowing.

### Instructions

- Detailed in `testing_instructions.md`.
- Execute tests to validate functionality and identify gaps.

## 6. Future Improvements

- Refactor other files to follow best practices for maintainability.
- Enhance UI responsiveness and accessibility.
- Conduct a thorough security audit to identify and fix vulnerabilities.

---

This documentation consolidates key information to help users, administrators, and developers understand, set up, and maintain the Library Management System project.
