@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Discover Your Next Favorite Book</h1>
                <p class="lead mb-4">Explore our vast collection of books, borrow them for free, or purchase your favorites to keep forever.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('books.index') }}" class="btn btn-light btn-lg">Browse Books</a>
                    <a href="{{ route('authors.index') }}" class="btn btn-outline-light btn-lg">Meet Authors</a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <img src="{{ asset('images/hero-books.png') }}" alt="Books Collection" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Featured Books Section -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title">Featured Books</h2>
            <a href="{{ route('books.index') }}" class="btn btn-outline-primary">View All</a>
        </div>
        
        <div class="row">
            @foreach($featuredBooks as $book)
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 book-card">
                        <img src="{{ $book->cover_image_url }}" class="card-img-top" alt="{{ $book->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $book->name }}</h5>
                            <p class="card-text text-muted">{{ $book->author->name }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-primary">{{ $book->category }}</span>
                                <span class="fw-bold">${{ number_format($book->price, 2) }}</span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-grid gap-2">
                                <a href="{{ route('books.show', $book) }}" class="btn btn-outline-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center mb-5">Browse by Category</h2>
        
        <div class="row justify-content-center">
            @foreach($categories as $category)
                <div class="col-6 col-md-4 col-lg-2 mb-4">
                    <a href="{{ route('books.category', $category) }}" class="text-decoration-none">
                        <div class="card h-100 category-card text-center">
                            <div class="card-body">
                                <i class="fas fa-book fa-3x mb-3 text-primary"></i>
                                <h5 class="card-title">{{ $category }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Latest Books Section -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title">Latest Additions</h2>
            <a href="{{ route('books.latest') }}" class="btn btn-outline-primary">View All</a>
        </div>
        
        <div class="row">
            @foreach($latestBooks as $book)
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 book-card">
                        <img src="{{ $book->cover_image_url }}" class="card-img-top" alt="{{ $book->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $book->name }}</h5>
                            <p class="card-text text-muted">{{ $book->author->name }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-primary">{{ $book->category }}</span>
                                <span class="fw-bold">${{ number_format($book->price, 2) }}</span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-grid gap-2">
                                <a href="{{ route('books.show', $book) }}" class="btn btn-outline-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Popular Authors Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title">Popular Authors</h2>
            <a href="{{ route('authors.index') }}" class="btn btn-outline-primary">View All</a>
        </div>
        
        <div class="row">
            @foreach($popularAuthors as $author)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 author-card">
                        <div class="row g-0">
                            <div class="col-4">
                                <img src="{{ $author->image_url }}" class="img-fluid rounded-start h-100 object-fit-cover" alt="{{ $author->name }}">
                            </div>
                            <div class="col-8">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $author->name }}</h5>
                                    <p class="card-text"><small class="text-muted">{{ $author->books_count }} {{ Str::plural('book', $author->books_count) }}</small></p>
                                    <a href="{{ route('authors.show', $author) }}" class="btn btn-sm btn-outline-primary">View Books</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">Our Services</h2>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100 service-card text-center">
                    <div class="card-body">
                        <i class="fas fa-book-reader fa-3x mb-3 text-primary"></i>
                        <h4 class="card-title">Book Borrowing</h4>
                        <p class="card-text">Borrow books for free and enjoy reading them for up to two weeks.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100 service-card text-center">
                    <div class="card-body">
                        <i class="fas fa-shopping-cart fa-3x mb-3 text-primary"></i>
                        <h4 class="card-title">Book Purchase</h4>
                        <p class="card-text">Buy your favorite books and add them to your personal collection.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100 service-card text-center">
                    <div class="card-body">
                        <i class="fas fa-file-pdf fa-3x mb-3 text-primary"></i>
                        <h4 class="card-title">Digital Access</h4>
                        <p class="card-text">Access digital versions of books for reading on your devices.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="mb-4">Subscribe to Our Newsletter</h2>
                <p class="mb-4">Stay updated with our latest book additions, author events, and special offers.</p>
                
                <form class="row g-3 justify-content-center">
                    <div class="col-md-8">
                        <input type="email" class="form-control form-control-lg" placeholder="Your email address">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-light btn-lg w-100">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection