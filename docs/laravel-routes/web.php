<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'contactSubmit'])->name('contact.submit');

// Book routes
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
Route::get('/books/category/{category}', [BookController::class, 'byCategory'])->name('books.category');
Route::get('/latest-books', [BookController::class, 'latest'])->name('books.latest');
Route::get('/search', [BookController::class, 'search'])->name('books.search');

// Author routes
Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
Route::get('/authors/{author}', [AuthorController::class, 'show'])->name('authors.show');

// Authentication routes
Auth::routes();

// Authenticated user routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{book}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    
    // Borrow
    Route::get('/borrow', [BorrowController::class, 'index'])->name('borrow.index');
    Route::post('/borrow/request/{book}', [BorrowController::class, 'request'])->name('borrow.request');
    Route::delete('/borrow/{borrow}', [BorrowController::class, 'cancel'])->name('borrow.cancel');
    Route::patch('/borrow/{borrow}/return', [BorrowController::class, 'return'])->name('borrow.return');
    
    // Payment
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/process', [PaymentController::class, 'process'])->name('payments.process');
    Route::get('/payments/{payment}/success', [PaymentController::class, 'success'])->name('payments.success');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin dashboard
    Route::get('/', [HomeController::class, 'adminDashboard'])->name('dashboard');
    
    // Book management
    Route::resource('books', BookController::class)->except(['show']);
    
    // Author management
    Route::resource('authors', AuthorController::class)->except(['show']);
    
    // Borrow management
    Route::get('/borrows', [BorrowController::class, 'adminIndex'])->name('borrows.index');
    Route::patch('/borrows/{borrow}/approve', [BorrowController::class, 'approve'])->name('borrows.approve');
    Route::patch('/borrows/{borrow}/decline', [BorrowController::class, 'decline'])->name('borrows.decline');
    
    // Payment management
    Route::get('/payments', [PaymentController::class, 'adminIndex'])->name('payments.index');
    Route::get('/payments/{payment}', [PaymentController::class, 'adminShow'])->name('payments.show');
    
    // User management
    Route::resource('users', UserController::class)->except(['show']);
});