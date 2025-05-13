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

## 3. Usage Instructions and User Roles

### User Roles

- **Regular User**: Browse books, borrow books, make purchases.
- **Admin**: Manage books, authors, borrow requests, users.

### Default Admin Account (Laravel Migration)

- Email: admin@example.com
- Password: password

### Usage Examples

Refer to the README.md for PHP code examples on user authentication, book management, borrowing system, and shopping cart.

## 4. Architecture and Class Structure Overview

### File Structure

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

### Class Structure

- **Database**: Database connection and query methods
- **User**: User authentication and management
- **Book**: Book management
- **Borrow**: Book borrowing operations
- **Cart**: Shopping cart and payment operations
- **Helper**: Utility functions

## 5. Database Schema and Relationships

Refer to `docs/database-structure.md` for detailed table schemas, indexes, relationships, backup and restore functionality, migration scripts, and security considerations.

### Key Tables

- `users`
- `authors`
- `all_books`
- `borrow_history`
- `cart`
- `payments`

### Relationships

- `all_books.author_id` → `authors.id`
- `borrow_history.book_id` → `all_books.id`
- `cart.book_id` → `all_books.id`

## 6. Migration and Backup Procedures

- Use `database/migrate.php` to create or update the database schema.
- Backup and restore using `includes/DatabaseBackup.php` and admin interface `admin/database-backup.php`.
- Backups stored in `database/backups` directory.

## 7. Security Considerations

- Passwords hashed using PHP's `password_hash()`.
- Database credentials stored in `config/config.php`.
- Input validation and sanitization before queries.
- Prepared statements used to prevent SQL injection.
- CSRF protection and role-based access control implemented.
- Secure file uploads and session security enhanced.
- Rate limiting for login attempts.

## 8. Existing Documentation References and How to Use Them

- `README.md`: Project overview and basic usage.
- `docs/implementation-progress.md`: Development phases and progress.
- `docs/database-structure.md`: Database schema details.
- `docs/laravel-migration-README.md`: Laravel migration guide.
- Other docs in `docs/` directory cover Laravel components, API, controllers, middleware, migrations, models, policies, providers, repositories, requests, routes, seeders, views, and more.

## 9. Future Improvements and Roadmap

Refer to `docs/implementation-progress.md` and `ROADMAP.md` for planned features and improvements including:

- Frontend improvements and UI enhancements
- Advanced search functionality
- User profile management
- Email notifications
- Book ratings and reviews
- Reporting system
- Admin dashboard enhancements
- Testing and deployment plans
- Documentation and training sessions
- Consider adding diagrams for database schema and architecture (ERD, class diagrams)
- Add CI/CD and Docker information if applicable in the future roadmap

---

This documentation consolidates key information to help users, administrators, and developers understand, set up, and maintain the Library Management System project.
