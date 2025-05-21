@props(['book'])

<div class="card h-100 shadow-sm book-card">
    <img src="{{ $book->cover_image ? asset($book->cover_image) : asset('images/books1.png') }}" class="card-img-top" alt="{{ $book->name }}">
    <div class="card-body d-flex flex-column">
        <h5 class="card-title">{{ $book->name }}</h5>
        <p class="card-text text-muted mb-1">{{ $book->author }}</p>
        <p class="card-text text-secondary small mb-2">{{ $book->category }}</p>
        <a href="{{ route('books.show', $book->id) }}" class="btn btn-primary mt-auto">View Details</a>
    </div>
</div>
