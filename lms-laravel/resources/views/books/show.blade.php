@extends('layouts.app')

@section('content')
<div class="container py-4 fade-in">
    <h1>{{ $book->name }}</h1>
    <div class="row g-4">
        <div class="col-md-4">
            <img src="{{ $book->cover_image ? asset($book->cover_image) : asset('images/book-placeholder.jpg') }}" class="img-fluid rounded shadow-sm" alt="{{ $book->name }}">
        </div>
        <div class="col-md-8">
            <h4>Author: {{ $book->author }}</h4>
            <p><strong>Category:</strong> {{ $book->category }}</p>
            <p><strong>Description:</strong> {{ $book->description }}</p>
            <p><strong>Published:</strong> {{ $book->published_year ?? 'N/A' }}</p>
            <a href="{{ route('books.index') }}" class="btn btn-secondary mt-3">Back to All Books</a>
        </div>
    </div>
</div>
@endsection
