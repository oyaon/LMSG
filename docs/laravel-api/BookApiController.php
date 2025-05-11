<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookCollection;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookApiController extends Controller
{
    /**
     * Display a listing of the books.
     */
    public function index(Request $request)
    {
        $query = Book::with('author');
        
        // Apply search filter if provided
        if ($request->has('search')) {
            $query->search($request->search);
        }
        
        // Apply category filter if provided
        if ($request->has('category') && $request->category !== 'all') {
            $query->category($request->category);
        }
        
        // Get books with pagination
        $books = $query->latest()->paginate(12);
        
        return new BookCollection($books);
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $validated = $request->validated();
        
        // Handle author
        if (empty($validated['author_id']) && !empty($validated['author'])) {
            // Check if author exists
            $author = \App\Models\Author::where('name', $validated['author'])->first();
            
            if ($author) {
                $validated['author_id'] = $author->id;
            } else {
                // Create new author
                $newAuthor = \App\Models\Author::create(['name' => $validated['author']]);
                $validated['author_id'] = $newAuthor->id;
            }
        }
        
        // Handle PDF upload
        if ($request->hasFile('pdf')) {
            $pdfName = \Illuminate\Support\Str::uuid() . '.' . $request->pdf->extension();
            $request->pdf->storeAs('pdfs', $pdfName, 'public');
            $validated['pdf'] = $pdfName;
        }
        
        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $imageName = \Illuminate\Support\Str::uuid() . '.' . $request->cover_image->extension();
            $request->cover_image->storeAs('covers', $imageName, 'public');
            $validated['cover_image'] = $imageName;
        }
        
        // Create book
        $book = Book::create($validated);
        
        return new BookResource($book);
    }

    /**
     * Display the specified book.
     */
    public function show(Book $book)
    {
        $book->load('author');
        
        return new BookResource($book);
    }

    /**
     * Update the specified book in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $validated = $request->validated();
        
        // Handle author
        if (empty($validated['author_id']) && !empty($validated['author'])) {
            // Check if author exists
            $author = \App\Models\Author::where('name', $validated['author'])->first();
            
            if ($author) {
                $validated['author_id'] = $author->id;
            } else {
                // Create new author
                $newAuthor = \App\Models\Author::create(['name' => $validated['author']]);
                $validated['author_id'] = $newAuthor->id;
            }
        }
        
        // Handle PDF upload
        if ($request->hasFile('pdf')) {
            // Delete old file if exists
            if ($book->pdf) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('pdfs/' . $book->pdf);
            }
            
            $pdfName = \Illuminate\Support\Str::uuid() . '.' . $request->pdf->extension();
            $request->pdf->storeAs('pdfs', $pdfName, 'public');
            $validated['pdf'] = $pdfName;
        }
        
        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old file if exists
            if ($book->cover_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('covers/' . $book->cover_image);
            }
            
            $imageName = \Illuminate\Support\Str::uuid() . '.' . $request->cover_image->extension();
            $request->cover_image->storeAs('covers', $imageName, 'public');
            $validated['cover_image'] = $imageName;
        }
        
        // Update book
        $book->update($validated);
        
        return new BookResource($book);
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy(Book $book)
    {
        // Check authorization
        $this->authorize('delete', $book);
        
        // Delete PDF file if exists
        if ($book->pdf) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete('pdfs/' . $book->pdf);
        }
        
        // Delete cover image if exists
        if ($book->cover_image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete('covers/' . $book->cover_image);
        }
        
        // Delete book
        $book->delete();
        
        return response()->json(['message' => 'Book deleted successfully'], Response::HTTP_OK);
    }

    /**
     * Get all book categories.
     */
    public function categories()
    {
        $categories = Book::getCategories();
        
        return response()->json(['categories' => $categories], Response::HTTP_OK);
    }

    /**
     * Get books by category.
     */
    public function byCategory($category, Request $request)
    {
        $books = Book::with('author')
            ->category($category)
            ->latest()
            ->paginate(12);
        
        return new BookCollection($books);
    }

    /**
     * Search for books.
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
        ]);
        
        $books = Book::with('author')
            ->search($request->query)
            ->latest()
            ->paginate(12);
        
        return new BookCollection($books);
    }
}