<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Exception;

class BookService
{
    /**
     * Add a new book with file uploads and author handling
     *
     * @param array $bookData Associative array with keys: name, author, category, description, quantity, price
     * @param UploadedFile|null $pdfFile
     * @param UploadedFile|null $coverImageFile
     * @return Book
     * @throws Exception
     */
    public function addBook(array $bookData, ?UploadedFile $pdfFile = null, ?UploadedFile $coverImageFile = null): Book
    {
        return DB::transaction(function () use ($bookData, $pdfFile, $coverImageFile) {
            // Get or create author
            $author = $this->getOrCreateAuthor($bookData['author']);

            // Prepare book data
            $book = new Book();
            $book->name = $bookData['name'];
            $book->author_id = $author->id;
            $book->author = $bookData['author'];
            $book->category = $bookData['category'];
            $book->description = $bookData['description'];
            $book->quantity = $bookData['quantity'];
            $book->price = $bookData['price'] ?? 0;

            // Handle file uploads
            if ($pdfFile) {
                $book->pdf = $this->storeFile($pdfFile, 'pdfs');
            }

            if ($coverImageFile) {
                $book->cover_image = $this->storeFile($coverImageFile, 'book_covers');
            }

            $book->save();

            return $book;
        });
    }

    /**
     * Get or create an author by name
     *
     * @param string $authorName
     * @return Author
     */
    protected function getOrCreateAuthor(string $authorName): Author
    {
        return Author::firstOrCreate(['name' => $authorName]);
    }

    /**
     * Store uploaded file in specified directory
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string Stored file path
     * @throws Exception
     */
    protected function storeFile(UploadedFile $file, string $directory): string
    {
        $maxFileSize = config('filesystems.max_file_size', 10485760); // 10MB default

        if ($file->getSize() > $maxFileSize) {
            throw new Exception("File size exceeds maximum allowed size of " . ($maxFileSize / 1024 / 1024) . " MB");
        }

        return $file->store($directory, 'public');
    }
}
