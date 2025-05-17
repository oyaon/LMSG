<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    // List all books with search, filter, and pagination
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $category = $request->input('category', '');
        $perPage = (int) $request->input('per_page', 12);
        $perPage = $perPage > 0 ? $perPage : 12;
        $booksQuery = Book::query();
        if ($query) {
            $booksQuery->where('name', 'like', "%$query%")
                ->orWhere('author', 'like', "%$query%")
                ->orWhere('category', 'like', "%$query%")
                ->orWhere('description', 'like', "%$query%")
                ;
        }
        if ($category) {
            $booksQuery->where('category', $category);
        }
        $books = $booksQuery->orderBy('name', 'asc')->paginate($perPage)->withQueryString();
        $categories = Book::select('category')->distinct()->pluck('category');
        return view('books.index', compact('books', 'categories', 'query', 'category', 'perPage'));
    }

    // Show book details
    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('books.show', compact('book'));
    }
}
