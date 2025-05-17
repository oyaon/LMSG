@extends('layouts.app')

@section('content')
<div class="container py-4 fade-in">
    <h1 class="mb-4">Search Results</h1>
    <form method="GET" action="{{ route('search.index') }}" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control" placeholder="Search for books, authors, or categories..." value="{{ $query }}">
            </div>
            <div class="col-md-2">
                <select name="category" class="form-control">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" @if($cat == $category) selected @endif>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="author" class="form-control">
                    <option value="">All Authors</option>
                    @foreach($authors as $a)
                        <option value="{{ $a }}" @if($a == $author) selected @endif>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="sort_option" class="form-control">
                    @foreach($sortOptions as $key => $label)
                        <option value="{{ $key }}" @if($key == $sortOption) selected @endif>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </div>
    </form>
    @if($books->count() === 0)
        <div class="alert alert-warning">No books found matching your search criteria.</div>
    @else
        <p class="text-muted mb-4">Found {{ $books->total() }} book{{ $books->total() !== 1 ? 's' : '' }} matching your search criteria.</p>
        <div class="row">
            @foreach($books as $book)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm book-card">
                        <img src="{{ $book->cover_image ? asset($book->cover_image) : asset('images/books1.png') }}" class="card-img-top" alt="{{ $book->name }}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $book->name }}</h5>
                            <p class="card-text text-muted mb-1">{{ $book->author }}</p>
                            <p class="card-text text-secondary small mb-2">{{ $book->category }}</p>
                            <a href="{{ route('books.show', $book->id) }}" class="btn btn-primary mt-auto">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $books->links() }}
        </div>
    @endif
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Search Tips</h5>
        </div>
        <div class="card-body">
            <ul class="mb-0">
                <li>Use specific keywords for better results</li>
                <li>Search by book title, author name, or category</li>
                <li>Use filters to narrow down your search</li>
                <li>Try different sorting options to find what you're looking for</li>
                <li>If you can't find what you're looking for, try using fewer keywords</li>
            </ul>
        </div>
    </div>
</div>
@endsection
