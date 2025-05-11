<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookApiController;
use App\Http\Controllers\Api\AuthorApiController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Books
Route::get('/books', [BookApiController::class, 'index']);
Route::get('/books/{book}', [BookApiController::class, 'show']);
Route::get('/books/category/{category}', [BookApiController::class, 'byCategory']);
Route::get('/books/search', [BookApiController::class, 'search']);
Route::get('/categories', [BookApiController::class, 'categories']);

// Authors
Route::get('/authors', [AuthorApiController::class, 'index']);
Route::get('/authors/{author}', [AuthorApiController::class, 'show']);
Route::get('/authors/{author}/books', [AuthorApiController::class, 'books']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Cart
    Route::get('/cart', [CartApiController::class, 'index']);
    Route::post('/cart/add/{book}', [CartApiController::class, 'add']);
    Route::delete('/cart/{cart}', [CartApiController::class, 'remove']);
    Route::delete('/cart', [CartApiController::class, 'clear']);
    
    // Borrow
    Route::get('/borrow', [BorrowApiController::class, 'index']);
    Route::post('/borrow/request/{book}', [BorrowApiController::class, 'request']);
    Route::delete('/borrow/{borrow}', [BorrowApiController::class, 'cancel']);
    Route::patch('/borrow/{borrow}/return', [BorrowApiController::class, 'return']);
    
    // Payment
    Route::get('/payments', [PaymentApiController::class, 'index']);
    Route::post('/payments/process', [PaymentApiController::class, 'process']);
    
    // Admin routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        // Books management
        Route::post('/books', [BookApiController::class, 'store']);
        Route::put('/books/{book}', [BookApiController::class, 'update']);
        Route::delete('/books/{book}', [BookApiController::class, 'destroy']);
        
        // Authors management
        Route::post('/authors', [AuthorApiController::class, 'store']);
        Route::put('/authors/{author}', [AuthorApiController::class, 'update']);
        Route::delete('/authors/{author}', [AuthorApiController::class, 'destroy']);
        
        // Borrow management
        Route::get('/borrows', [BorrowApiController::class, 'adminIndex']);
        Route::patch('/borrows/{borrow}/approve', [BorrowApiController::class, 'approve']);
        Route::patch('/borrows/{borrow}/decline', [BorrowApiController::class, 'decline']);
        
        // User management
        Route::get('/users', [UserApiController::class, 'index']);
        Route::get('/users/{user}', [UserApiController::class, 'show']);
        Route::put('/users/{user}', [UserApiController::class, 'update']);
        Route::delete('/users/{user}', [UserApiController::class, 'destroy']);
    });
});