# Laravel Migration Guide for LMS

This guide provides step-by-step instructions for migrating the Library Management System (LMS) from a custom PHP application to Laravel.

## Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher
- Node.js and NPM (for frontend assets)
- Git (optional but recommended)

## Step 1: Create a New Laravel Project

```bash
# Create a new Laravel project
composer create-project laravel/laravel lms-laravel

# Navigate to the project directory
cd lms-laravel
```

## Step 2: Configure Environment Variables

1. Copy the `.env.example` file to `.env`:
   ```bash
   cp .env.example .env
   ```

2. Generate an application key:
   ```bash
   php artisan key:generate
   ```

3. Update the `.env` file with your database credentials:
   ```
   APP_NAME="Library Management System"
   APP_URL=http://localhost:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=lms
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. Configure a second database connection for the old database in `config/database.php`:
   ```php
   'mysql_old' => [
       'driver' => 'mysql',
       'url' => env('DATABASE_URL'),
       'host' => env('DB_HOST', '127.0.0.1'),
       'port' => env('DB_PORT', '3306'),
       'database' => env('DB_OLD_DATABASE', 'lms_old'),
       'username' => env('DB_USERNAME', 'forge'),
       'password' => env('DB_PASSWORD', ''),
       'unix_socket' => env('DB_SOCKET', ''),
       'charset' => 'utf8mb4',
       'collation' => 'utf8mb4_unicode_ci',
       'prefix' => '',
       'prefix_indexes' => true,
       'strict' => true,
       'engine' => null,
       'options' => extension_loaded('pdo_mysql') ? array_filter([
           PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
       ]) : [],
   ],
   ```

5. Add the old database name to your `.env` file:
   ```
   DB_OLD_DATABASE=lms_old
   ```

## Step 3: Create Database Migrations

1. Create migrations for all tables:
   ```bash
   php artisan make:migration create_users_table
   php artisan make:migration create_authors_table
   php artisan make:migration create_books_table
   php artisan make:migration create_borrow_history_table
   php artisan make:migration create_cart_table
   php artisan make:migration create_payments_table
   ```

2. Copy the migration files from the `docs/laravel-migrations` directory to your Laravel project's `database/migrations` directory.

3. Run the migrations:
   ```bash
   php artisan migrate
   ```

## Step 4: Create Models

1. Create models for all entities:
   ```bash
   php artisan make:model User
   php artisan make:model Author
   php artisan make:model Book
   php artisan make:model BorrowHistory
   php artisan make:model Cart
   php artisan make:model Payment
   ```

2. Copy the model files from the `docs/laravel-models` directory to your Laravel project's `app/Models` directory.

## Step 5: Create Seeders

1. Create seeders for all entities:
   ```bash
   php artisan make:seeder UserSeeder
   php artisan make:seeder AuthorSeeder
   php artisan make:seeder BookSeeder
   php artisan make:seeder BorrowHistorySeeder
   php artisan make:seeder CartSeeder
   php artisan make:seeder PaymentSeeder
   ```

2. Copy the seeder files from the `docs/laravel-seeders` directory to your Laravel project's `database/seeders` directory.

3. Update the `DatabaseSeeder.php` file to call all seeders.

4. Run the seeders:
   ```bash
   php artisan db:seed
   ```

## Step 6: Create Controllers

1. Create controllers for all functionality:
   ```bash
   php artisan make:controller HomeController
   php artisan make:controller BookController
   php artisan make:controller AuthorController
   php artisan make:controller BorrowController
   php artisan make:controller CartController
   php artisan make:controller PaymentController
   php artisan make:controller ProfileController
   php artisan make:controller AdminController
   php artisan make:controller UserController
   ```

2. Copy the controller files from the `docs/laravel-controllers` directory to your Laravel project's `app/Http/Controllers` directory.

## Step 7: Create Form Requests

1. Create form request validation classes:
   ```bash
   php artisan make:request ProfileUpdateRequest
   php artisan make:request PasswordUpdateRequest
   php artisan make:request StoreBookRequest
   php artisan make:request UpdateBookRequest
   ```

2. Copy the form request files from the `docs/laravel-requests` directory to your Laravel project's `app/Http/Requests` directory.

## Step 8: Create Policies

1. Create authorization policies:
   ```bash
   php artisan make:policy BookPolicy --model=Book
   php artisan make:policy BorrowHistoryPolicy --model=BorrowHistory
   ```

2. Copy the policy files from the `docs/laravel-policies` directory to your Laravel project's `app/Policies` directory.

## Step 9: Create Middleware

1. Create the admin middleware:
   ```bash
   php artisan make:middleware AdminMiddleware
   ```

2. Copy the middleware file from the `docs/laravel-middleware` directory to your Laravel project's `app/Http/Middleware` directory.

3. Register the middleware in `app/Http/Kernel.php`:
   ```php
   protected $routeMiddleware = [
       // ...
       'admin' => \App\Http\Middleware\AdminMiddleware::class,
   ];
   ```

## Step 10: Configure Routes

1. Copy the route files from the `docs/laravel-routes` directory to your Laravel project's `routes` directory.

## Step 11: Create Views

1. Create the necessary directories for views:
   ```bash
   mkdir -p resources/views/layouts
   mkdir -p resources/views/books
   mkdir -p resources/views/authors
   mkdir -p resources/views/cart
   mkdir -p resources/views/borrow
   mkdir -p resources/views/payments
   mkdir -p resources/views/profile
   mkdir -p resources/views/admin/books
   mkdir -p resources/views/admin/authors
   mkdir -p resources/views/admin/borrows
   mkdir -p resources/views/admin/payments
   mkdir -p resources/views/admin/users
   mkdir -p resources/views/auth
   ```

2. Copy the view files from the `docs/laravel-views` directory to your Laravel project's `resources/views` directory.

## Step 12: Configure File Storage

1. Create symbolic link for storage:
   ```bash
   php artisan storage:link
   ```

2. Create directories for file uploads:
   ```bash
   mkdir -p storage/app/public/covers
   mkdir -p storage/app/public/pdfs
   mkdir -p storage/app/public/authors
   ```

3. Copy existing files from the old application to the new storage directories.

## Step 13: Create CSS and JavaScript Files

1. Copy the CSS files from the `docs/laravel-assets/css` directory to your Laravel project's `public/css` directory.

2. Create JavaScript files as needed in the `public/js` directory.

## Step 14: Configure Authentication

1. Install Laravel Breeze for authentication scaffolding:
   ```bash
   composer require laravel/breeze --dev
   php artisan breeze:install
   ```

2. Customize the authentication views and controllers as needed.

## Step 15: Create API Resources

1. Create API resources for models:
   ```bash
   php artisan make:resource BookResource
   php artisan make:resource BookCollection
   ```

2. Copy the resource files from the `docs/laravel-api` directory to your Laravel project's `app/Http/Resources` directory.

## Step 16: Create Repositories

1. Create repository interfaces and implementations:
   ```bash
   mkdir -p app/Repositories/Interfaces
   ```

2. Copy the repository files from the `docs/laravel-repositories` directory to your Laravel project's `app/Repositories` directory.

## Step 17: Configure Service Providers

1. Update the `AppServiceProvider.php` file with the content from `docs/laravel-providers/AppServiceProvider.php`.

2. Create a new service provider for repositories:
   ```bash
   php artisan make:provider RepositoryServiceProvider
   ```

3. Copy the repository service provider from `docs/laravel-providers/RepositoryServiceProvider.php` to your Laravel project's `app/Providers` directory.

4. Register the service provider in `config/app.php`:
   ```php
   'providers' => [
       // ...
       App\Providers\RepositoryServiceProvider::class,
   ],
   ```

## Step 18: Write Tests

1. Create feature tests for main functionality:
   ```bash
   php artisan make:test BookControllerTest
   php artisan make:test AuthorControllerTest
   php artisan make:test BorrowControllerTest
   php artisan make:test CartControllerTest
   php artisan make:test PaymentControllerTest
   ```

2. Create unit tests for models:
   ```bash
   php artisan make:test BookTest --unit
   php artisan make:test AuthorTest --unit
   php artisan make:test BorrowHistoryTest --unit
   php artisan make:test CartTest --unit
   php artisan make:test PaymentTest --unit
   ```

3. Run the tests:
   ```bash
   php artisan test
   ```

## Step 19: Optimize for Production

1. Optimize the application for production:
   ```bash
   php artisan optimize
   php artisan route:cache
   php artisan config:cache
   php artisan view:cache
   ```

2. Compile assets for production:
   ```bash
   npm run build
   ```

## Step 20: Deploy the Application

1. Set up the production server with PHP, MySQL, and a web server (Apache/Nginx).

2. Configure the web server to point to the `public` directory of your Laravel application.

3. Set proper file permissions:
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

4. Configure environment variables for production.

5. Run migrations and seeders on the production database.

## Conclusion

Following these steps will help you successfully migrate the Library Management System from a custom PHP application to Laravel. The migration will result in a more maintainable, secure, and feature-rich application.