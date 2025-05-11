# Laravel Migration Plan for LMS

This document outlines the step-by-step plan to migrate the existing Library Management System (LMS) from a custom PHP application to Laravel.

## 1. Project Setup

1. Create a new Laravel project:
   ```bash
   composer create-project laravel/laravel laravel-lms
   ```

2. Configure the `.env` file with database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=bms
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. Set up application name and URL:
   ```
   APP_NAME="Library Management System"
   APP_URL=http://localhost/LMS/laravel-lms/public
   ```

## 2. Database Migration

1. Create migration files for all tables:
   - users
   - authors
   - all_books (rename to books)
   - borrow_history
   - cart
   - payments

2. Create seeders to import existing data

## 3. Models

Create Eloquent models with relationships:

1. User
   - hasMany BorrowHistory
   - hasMany Cart
   - hasMany Payment

2. Author
   - hasMany Book

3. Book
   - belongsTo Author
   - hasMany BorrowHistory
   - hasMany Cart

4. BorrowHistory
   - belongsTo User
   - belongsTo Book

5. Cart
   - belongsTo User
   - belongsTo Book

6. Payment
   - belongsTo User

## 4. Authentication

1. Use Laravel's built-in authentication:
   ```bash
   php artisan make:auth
   ```

2. Customize the auth controllers to match existing functionality
3. Implement admin middleware

## 5. Controllers

Create controllers for each major functionality:

1. HomeController
2. BookController
3. AuthorController
4. BorrowController
5. CartController
6. PaymentController
7. AdminController

## 6. Views

1. Create Blade templates based on existing views
2. Implement layouts for:
   - Main layout
   - Admin layout
   - Auth layout

3. Convert existing CSS/JS to Laravel asset structure

## 7. File Storage

1. Configure Laravel's file storage for:
   - Book covers
   - PDF files
   - Author images

2. Update file upload logic to use Laravel's Storage facade

## 8. Routes

Define routes in `routes/web.php`:

1. Public routes
2. Authenticated user routes
3. Admin routes

## 9. Middleware

Create middleware for:

1. Admin access
2. User authentication

## 10. Testing

1. Create feature tests for main functionality
2. Create unit tests for models

## 11. Deployment

1. Configure production environment
2. Set up proper file permissions
3. Configure web server (Apache/Nginx)

## Migration Timeline

1. Database migration: 2 days
2. Models and relationships: 2 days
3. Authentication: 1 day
4. Controllers and views: 5 days
5. File storage: 1 day
6. Testing: 2 days
7. Deployment: 1 day

Total estimated time: 14 days