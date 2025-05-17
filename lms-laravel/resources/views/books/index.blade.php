@extends('layouts.app')

@section('content')
<div class="container py-4 fade-in">
    <h1>All Books</h1>
    <form method="GET" action="{{ route('books.index') }}" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control" placeholder="Search for books..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-control">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" @if($cat == $category) selected @endif>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="per_page" class="form-control">
                    <option value="12" @if($perPage==12) selected @endif>12</option>
                    <option value="24" @if($perPage==24) selected @endif>24</option>
                    <option value="48" @if($perPage==48) selected @endif>48</option>
                    <option value="96" @if($perPage==96) selected @endif>96</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
            <div class="col-md-1">
                @if(request('q') || request('category'))
                    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
                @endif
            </div>
        </div>
    </form>
    <div class="row g-3" id="book-list">
        @forelse($books as $book)
            <div class="col-sm-6 col-md-4 col-lg-3">
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
        @empty
            <p class="text-center">No books found matching your criteria.</p>
        @endforelse
    </div>
    <div class="mt-4">
        {{ $books->links() }}
    </div>
</div>
@endsection
