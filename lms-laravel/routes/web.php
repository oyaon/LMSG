<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/user-profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/user-profile/upload', [ProfileController::class, 'uploadProfileImage'])->name('profile.upload');
    Route::post('/borrow/{bookId}', [BorrowController::class, 'requestBorrow'])->name('borrow.request');
    Route::get('/borrow/history', [BorrowController::class, 'history'])->name('borrow.history');
    Route::get('/borrow/{bookId}/request', [BorrowController::class, 'requestForm'])->name('borrow.form');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{bookId}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{cartId}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{cartId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/donate', [DonationController::class, 'index'])->name('donate.index');
    Route::post('/donate/money', [DonationController::class, 'donateMoney'])->name('donate.money');
    Route::post('/donate/books', [DonationController::class, 'donateBooks'])->name('donate.books');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

require __DIR__.'/auth.php';
