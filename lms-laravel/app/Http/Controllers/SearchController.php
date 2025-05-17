<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $category = $request->input('category', '');
        $author = $request->input('author', '');
        $sortOption = $request->input('sort_option', 'name_asc');
        $perPage = 12;
        $sortParts = explode('_', $sortOption);
        $sortBy = $sortParts[0] ?? 'name';
        $sortOrder = $sortParts[1] ?? 'asc';

        $booksQuery = Book::query();
        if ($query) {
            $booksQuery->where(function($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                  ->orWhere('author', 'like', "%$query%")
                  ->orWhere('category', 'like', "%$query%")
                  ->orWhere('description', 'like', "%$query%")
                  ;
            });
        }
        if ($category) {
            $booksQuery->where('category', $category);
        }
        if ($author) {
            $booksQuery->where('author', $author);
        }
        $books = $booksQuery->orderBy($sortBy, $sortOrder)->paginate($perPage)->withQueryString();
        $categories = Book::select('category')->distinct()->pluck('category');
        $authors = Book::select('author')->distinct()->pluck('author');
        $sortOptions = [
            'name_asc' => 'Title (A-Z)',
            'name_desc' => 'Title (Z-A)',
            'author_asc' => 'Author (A-Z)',
            'author_desc' => 'Author (Z-A)',
            'price_asc' => 'Price (Low to High)',
            'price_desc' => 'Price (High to Low)'
        ];
        return view('search.index', compact('books', 'categories', 'authors', 'query', 'category', 'author', 'sortOption', 'sortOptions'));
    }
}