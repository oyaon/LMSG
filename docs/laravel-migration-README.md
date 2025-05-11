# Library Management System - Laravel Migration

This repository contains the Laravel migration of the Library Management System (LMS).

## Overview

The Library Management System is a web application that allows users to browse, borrow, and purchase books. Administrators can manage books, authors, borrow requests, and users.

## Features

- User authentication and registration
- Book browsing and searching
- Author profiles
- Book borrowing system
- Shopping cart and payment processing
- Admin dashboard for managing the system

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher
- Node.js and NPM (for frontend assets)

## Installation

1. Clone the repository:
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

4. Create a copy of the `.env.example` file:
   ```bash
   cp .env.example .env
   ```

5. Generate an application key:
   ```bash
   php artisan key:generate
   ```

6. Configure your database in the `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=lms
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. Run the migrations:
   ```bash
   php artisan migrate
   ```

8. Seed the database with sample data (optional):
   ```bash
   php artisan db:seed
   ```

9. Create symbolic link for storage:
   ```bash
   php artisan storage:link
   ```

10. Start the development server:
    ```bash
    php artisan serve
    ```

## Directory Structure

- `app/Models`: Contains Eloquent models
- `app/Http/Controllers`: Contains controllers
- `app/Http/Middleware`: Contains middleware
- `database/migrations`: Contains database migrations
- `resources/views`: Contains Blade templates
- `routes`: Contains route definitions
- `public`: Contains publicly accessible files
- `storage`: Contains uploaded files

## Usage

### User Roles

- **Regular User**: Can browse books, borrow books, and make purchases
- **Admin**: Can manage books, authors, borrow requests, and users

### Default Admin Account

- Email: admin@example.com
- Password: password

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgements

- Laravel - The PHP Framework for Web Artisans
- Bootstrap - Frontend framework
- Font Awesome - Icon toolkit