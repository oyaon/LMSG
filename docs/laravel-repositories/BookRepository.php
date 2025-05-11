<?php

namespace App\Repositories;

use App\Models\Book;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BookRepository implements BookRepositoryInterface
{
    /**
     * Get all books with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllBooks(int $perPage = 12): LengthAwarePaginator
    {
        return Book::with('author')
            ->latest()
            ->paginate($perPage);
    }
    
    /**
     * Get a book by ID.
     *
     * @param int $id
     * @return Book|null
     */
    public function getBookById(int $id): ?Book
    {
        return Book::with('author')->find($id);
    }
    
    /**
     * Create a new book.
     *
     * @param array $data
     * @return Book
     */
    public function createBook(array $data): Book
    {
        return Book::create($data);
    }
    
    /**
     * Update a book.
     *
     * @param Book $book
     * @param array $data
     * @return bool
     */
    public function updateBook(Book $book, array $data): bool
    {
        return $book->update($data);
    }
    
    /**
     * Delete a book.
     *
     * @param Book $book
     * @return bool
     */
    public function deleteBook(Book $book): bool
    {
        return $book->delete();
    }
    
    /**
     * Search for books.
     *
     * @param string $query
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function searchBooks(string $query, int $perPage = 12): LengthAwarePaginator
    {
        return Book::with('author')
            ->search($query)
            ->latest()
            ->paginate($perPage);
    }
    
    /**
     * Get books by category.
     *
     * @param string $category
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getBooksByCategory(string $category, int $perPage = 12): LengthAwarePaginator
    {
        return Book::with('author')
            ->category($category)
            ->latest()
            ->paginate($perPage);
    }
    
    /**
     * Get latest books.
     *
     * @param int $limit
     * @return Collection
     */
    public function getLatestBooks(int $limit = 10): Collection
    {
        return Book::with('author')
            ->latest()
            ->limit($limit)
            ->get();
    }
    
    /**
     * Get featured books.
     *
     * @param int $limit
     * @return Collection
     */
    public function getFeaturedBooks(int $limit = 4): Collection
    {
        return Book::with('author')
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
    
    /**
     * Get all categories.
     *
     * @return Collection
     */
    public function getAllCategories(): Collection
    {
        return Book::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
    }
    
    /**
     * Get related books.
     *
     * @param Book $book
     * @param int $limit
     * @return Collection
     */
    public function getRelatedBooks(Book $book, int $limit = 4): Collection
    {
        return Book::with('author')
            ->where('author_id', $book->author_id)
            ->where('id', '!=', $book->id)
            ->latest()
            ->limit($limit)
            ->get();
    }
}