@extends('layouts.app')

@section('title', $book->name)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Books</a></li>
            <li class="breadcrumb-item"><a href="{{ route('books.category', $book->category) }}">{{ $book->category }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $book->name }}</li>
        </ol>
    </nav>
    
    <div class="row">
        <!-- Book Cover and Actions -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="{{ $book->cover_image_url }}" class="card-img-top" alt="{{ $book->name }}">
                <div class="card-body">
                    <h5 class="card-title d-flex justify-content-between">
                        <span>Price</span>
                        <span class="text-primary">${{ number_format($book->price, 2) }}</span>
                    </h5>
                    <p class="card-text d-flex justify-content-between">
                        <span>Availability</span>
                        <span>
                            @if($book->quantity > 0)
                                <span class="badge bg-success">In Stock ({{ $book->quantity }})</span>
                            @else
                                <span class="badge bg-danger">Out of Stock</span>
                            @endif
                        </span>
                    </p>
                    
                    <div class="d-grid gap-2 mt-4">
                        @auth
                            <form action="{{ route('cart.add', $book) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100" {{ $book->quantity <= 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                                </button>
                            </form>
                            
                            <form action="{{ route('borrow.request', $book) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary w-100" {{ $book->quantity <= 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-book-reader me-2"></i> Borrow Book
                                </button>
                            </form>
                            
                            @if($book->pdf)
                                <a href="{{ $book->pdf_url }}" class="btn btn-outline-secondary w-100" target="_blank">
                                    <i class="fas fa-file-pdf me-2"></i> Preview PDF
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary w-100">
                                <i class="fas fa-sign-in-alt me-2"></i> Login to Purchase/Borrow
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Book Details -->
        <div class="col-md-8">
            <h1 class="mb-3">{{ $book->name }}</h1>
            
            <div class="d-flex align-items-center mb-3">
                <span class="badge bg-primary me-2">{{ $book->category }}</span>
                <span class="text-muted">
                    <i class="fas fa-user me-1"></i> 
                    <a href="{{ route('authors.show', $book->author) }}" class="text-decoration-none">
                        {{ $book->author->name }}
                    </a>
                </span>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="bookTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="author-tab" data-bs-toggle="tab" data-bs-target="#author" type="button" role="tab" aria-controls="author" aria-selected="false">Author</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="bookTabsContent">
                        <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                            <p>{{ $book->description }}</p>
                        </div>
                        <div class="tab-pane fade" id="author" role="tabpanel" aria-labelledby="author-tab">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ $book->author->image_url }}" alt="{{ $book->author->name }}" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                <div>
                                    <h5 class="mb-1">{{ $book->author->name }}</h5>
                                    <a href="{{ route('authors.show', $book->author) }}" class="btn btn-sm btn-outline-primary">View All Books</a>
                                </div>
                            </div>
                            <p>{{ $book->author->biography ?? 'No biography available for this author.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Related Books -->
            @if($relatedBooks->count() > 0)
                <h3 class="mb-3">More Books by {{ $book->author->name }}</h3>
                <div class="row">
                    @foreach($relatedBooks as $relatedBook)
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card h-100 book-card">
                                <img src="{{ $relatedBook->cover_image_url }}" class="card-img-top" alt="{{ $relatedBook->name }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $relatedBook->name }}</h5>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-primary">{{ $relatedBook->category }}</span>
                                        <span class="fw-bold">${{ number_format($relatedBook->price, 2) }}</span>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-top-0">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('books.show', $relatedBook) }}" class="btn btn-outline-primary">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection