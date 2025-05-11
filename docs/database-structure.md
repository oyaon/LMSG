# Library Management System Database Structure

This document provides a detailed overview of the database structure used in the Library Management System (LMS).

## Tables Overview

The LMS database consists of the following tables:

1. `users` - Stores user account information
2. `authors` - Stores author information
3. `all_books` - Stores book information
4. `borrow_history` - Tracks book borrowing history
5. `cart` - Manages shopping cart functionality
6. `payments` - Records payment transactions

## Table Schemas

### users

Stores user account information including authentication details and user type.

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key, auto-increment |
| first_name | VARCHAR(50) | User's first name |
| last_name | VARCHAR(50) | User's last name |
| user_name | VARCHAR(50) | Unique username |
| email | VARCHAR(100) | Unique email address |
| password | VARCHAR(255) | Hashed password |
| user_type | TINYINT | 0: Admin, 1: Regular User |
| created_at | TIMESTAMP | Account creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

### authors

Stores information about book authors.

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key, auto-increment |
| name | VARCHAR(100) | Author's name |
| biography | TEXT | Author's biography |
| image | VARCHAR(255) | Path to author's image |
| created_at | TIMESTAMP | Record creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

### all_books

Stores book information including metadata and file paths.

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key, auto-increment |
| name | VARCHAR(255) | Book title |
| author_id | INT | Foreign key to authors.id |
| author | VARCHAR(100) | Author name (for backward compatibility) |
| category | VARCHAR(50) | Book category |
| description | TEXT | Book description |
| quantity | INT | Available quantity |
| price | DECIMAL(10, 2) | Book price |
| pdf | VARCHAR(255) | Path to PDF file |
| cover_image | VARCHAR(255) | Path to cover image |
| created_at | TIMESTAMP | Record creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

### borrow_history

Tracks book borrowing history including issue and return dates.

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key, auto-increment |
| user_email | VARCHAR(100) | Borrower's email |
| book_id | INT | Foreign key to all_books.id |
| issue_date | DATE | Date book was issued |
| fine | DECIMAL(10, 2) | Fine amount (if any) |
| status | ENUM | 'Requested', 'Issued', 'Returned', 'Declined' |
| request_date | TIMESTAMP | Date of borrow request |
| return_date | DATE | Date book was returned |

### cart

Manages shopping cart functionality for book purchases.

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key, auto-increment |
| user_email | VARCHAR(100) | User's email |
| book_id | INT | Foreign key to all_books.id |
| date | DATE | Date added to cart |
| status | TINYINT | 0: In cart, 1: Purchased |

### payments

Records payment transactions for book purchases.

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key, auto-increment |
| user_email | VARCHAR(100) | User's email |
| book_ids | VARCHAR(255) | Comma-separated list of book IDs |
| amount | DECIMAL(10, 2) | Payment amount |
| payment_date | DATE | Date of payment |
| transaction_id | VARCHAR(100) | Payment transaction ID |
| payment_status | ENUM | 'Pending', 'Completed', 'Failed' |
| created_at | TIMESTAMP | Record creation timestamp |

## Indexes

The following indexes are used to optimize query performance:

| Table | Index Name | Columns | Type |
|-------|------------|---------|------|
| all_books | idx_book_name | name | INDEX |
| all_books | idx_book_author | author | INDEX |
| all_books | idx_book_category | category | INDEX |
| borrow_history | idx_borrow_status | status | INDEX |
| cart | idx_cart_user | user_email | INDEX |
| payments | idx_payment_user | user_email | INDEX |

## Relationships

The database includes the following relationships:

1. `all_books.author_id` → `authors.id` (Foreign Key)
2. `borrow_history.book_id` → `all_books.id` (Foreign Key)
3. `cart.book_id` → `all_books.id` (Foreign Key)

## Backup and Restore

The database can be backed up and restored using the DatabaseBackup class, which provides the following functionality:

- Creating manual backups
- Scheduling automatic backups
- Restoring from backups
- Managing backup files

Backups are stored in the `database/backups` directory and can be managed through the admin interface at `admin/database-backup.php`.

## Migration

The database schema can be created or updated using the migration script at `database/migrate.php`. This script creates all necessary tables and indexes based on the schema defined in `database/schema.sql`.

## Security Considerations

- Passwords are stored using PHP's `password_hash()` function with the default algorithm (currently bcrypt)
- Database credentials are stored in the configuration file at `config/config.php`
- Input validation and sanitization are performed before executing database queries
- Prepared statements are used for all database operations to prevent SQL injection