<?php

namespace App\Repositories\Interfaces;

use App\Models\Book;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface BookRepositoryInterface
{
    /**
     * Get all books with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllBooks(int $perPage = 12): LengthAwarePaginator;
    
    /**
     * Get a book by ID.
     *
     * @param int $id
     * @return Book|null
     */
    public function getBookById(int $id): ?Book;
    
    /**
     * Create a new book.
     *
     * @param array $data
     * @return Book
     */
    public function createBook(array $data): Book;
    
    /**
     * Update a book.
     *
     * @param Book $book
     * @param array $data
     * @return bool
     */
    public function updateBook(Book $book, array $data): bool;
    
    /**
     * Delete a book.
     *
     * @param Book $book
     * @return bool
     */
    public function deleteBook(Book $book): bool;
    
    /**
     * Search for books.
     *
     * @param string $query
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function searchBooks(string $query, int $perPage = 12): LengthAwarePaginator;
    
    /**
     * Get books by category.
     *
     * @param string $category
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getBooksByCategory(string $category, int $perPage = 12): LengthAwarePaginator;
    
    /**
     * Get latest books.
     *
     * @param int $limit
     * @return Collection
     */
    public function getLatestBooks(int $limit = 10): Collection;
    
    /**
     * Get featured books.
     *
     * @param int $limit
     * @return Collection
     */
    public function getFeaturedBooks(int $limit = 4): Collection;
    
    /**
     * Get all categories.
     *
     * @return Collection
     */
    public function getAllCategories(): Collection;
    
    /**
     * Get related books.
     *
     * @param Book $book
     * @param int $limit
     * @return Collection
     */
    public function getRelatedBooks(Book $book, int $limit = 4): Collection;
}