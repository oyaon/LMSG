@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Dashboard</h1>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Profile</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle p-3 me-3">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ Auth::user()->full_name }}</h5>
                            <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <h6>Account Type</h6>
                        <p>
                            @if(Auth::user()->isAdmin())
                                <span class="badge bg-danger">Administrator</span>
                            @else
                                <span class="badge bg-primary">Regular User</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Member Since</h6>
                        <p>{{ Auth::user()->created_at->format('F d, Y') }}</p>
                    </div>
                    
                    <div class="d-grid">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-user-edit me-2"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="activityTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="borrows-tab" data-bs-toggle="tab" data-bs-target="#borrows" type="button" role="tab" aria-controls="borrows" aria-selected="true">Borrows</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="purchases-tab" data-bs-toggle="tab" data-bs-target="#purchases" type="button" role="tab" aria-controls="purchases" aria-selected="false">Purchases</button>
                        </li>
                    </ul>
                    
                    <div class="tab-content pt-3" id="activityTabsContent">
                        <div class="tab-pane fade show active" id="borrows" role="tabpanel" aria-labelledby="borrows-tab">
                            @php
                                $recentBorrows = Auth::user()->borrowHistory()->latest()->limit(5)->get();
                            @endphp
                            
                            @if($recentBorrows->count() > 0)
                                <div class="list-group">
                                    @foreach($recentBorrows as $borrow)
                                        <div class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">{{ $borrow->book->name }}</h6>
                                                <small>{{ $borrow->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-1">Status: 
                                                @if($borrow->status == 'Requested')
                                                    <span class="badge bg-warning text-dark">Requested</span>
                                                @elseif($borrow->status == 'Issued')
                                                    <span class="badge bg-success">Issued</span>
                                                @elseif($borrow->status == 'Returned')
                                                    <span class="badge bg-info">Returned</span>
                                                @else
                                                    <span class="badge bg-danger">Declined</span>
                                                @endif
                                            </p>
                                            @if($borrow->status == 'Issued')
                                                <small>Due: {{ $borrow->return_date->format('M d, Y') }}</small>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="d-grid mt-3">
                                    <a href="{{ route('borrow.index') }}" class="btn btn-outline-primary">View All Borrows</a>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-book-reader fa-3x text-muted mb-3"></i>
                                    <p>You haven't borrowed any books yet.</p>
                                    <a href="{{ route('books.index') }}" class="btn btn-primary">Browse Books</a>
                                </div>
                            @endif
                        </div>
                        
                        <div class="tab-pane fade" id="purchases" role="tabpanel" aria-labelledby="purchases-tab">
                            @php
                                $recentPayments = Auth::user()->payments()->latest()->limit(5)->get();
                            @endphp
                            
                            @if($recentPayments->count() > 0)
                                <div class="list-group">
                                    @foreach($recentPayments as $payment)
                                        <div class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">Transaction #{{ $payment->transaction_id }}</h6>
                                                <small>{{ $payment->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-1">Amount: ${{ number_format($payment->amount, 2) }}</p>
                                            <small>Status: 
                                                @if($payment->payment_status == 'Completed')
                                                    <span class="badge bg-success">Completed</span>
                                                @elseif($payment->payment_status == 'Pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @else
                                                    <span class="badge bg-danger">Failed</span>
                                                @endif
                                            </small>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="d-grid mt-3">
                                    <a href="{{ route('payments.index') }}" class="btn btn-outline-primary">View All Payments</a>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <p>You haven't made any purchases yet.</p>
                                    <a href="{{ route('books.index') }}" class="btn btn-primary">Shop Books</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Cart</h5>
                    <a href="{{ route('cart.index') }}" class="btn btn-sm btn-primary">View Cart</a>
                </div>
                <div class="card-body">
                    @php
                        $cartItems = Auth::user()->activeCartItems()->with('book')->latest()->limit(3)->get();
                    @endphp
                    
                    @if($cartItems->count() > 0)
                        <div class="list-group">
                            @foreach($cartItems as $item)
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex">
                                        <img src="{{ $item->book->cover_image_url }}" alt="{{ $item->book->name }}" class="img-thumbnail me-3" style="width: 60px; height: 80px; object-fit: cover;">
                                        <div>
                                            <h6 class="mb-1">{{ $item->book->name }}</h6>
                                            <p class="mb-1">Price: ${{ number_format($item->book->price, 2) }}</p>
                                            <small>Added: {{ $item->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="d-grid mt-3">
                            <a href="{{ route('cart.checkout') }}" class="btn btn-success">Proceed to Checkout</a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <p>Your cart is empty.</p>
                            <a href="{{ route('books.index') }}" class="btn btn-primary">Add Books to Cart</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Recommended Books</h5>
                </div>
                <div class="card-body">
                    @php
                        // Get user's preferred categories based on borrow history
                        $userCategories = Auth::user()->borrowHistory()
                            ->join('books', 'borrow_history.book_id', '=', 'books.id')
                            ->pluck('books.category')
                            ->unique();
                        
                        // Get recommended books from those categories
                        $recommendedBooks = \App\Models\Book::whereIn('category', $userCategories)
                            ->inRandomOrder()
                            ->limit(3)
                            ->get();
                        
                        // If no recommendations based on history, get random popular books
                        if ($recommendedBooks->isEmpty()) {
                            $recommendedBooks = \App\Models\Book::inRandomOrder()->limit(3)->get();
                        }
                    @endphp
                    
                    <div class="row">
                        @foreach($recommendedBooks as $book)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <img src="{{ $book->cover_image_url }}" class="card-img-top" alt="{{ $book->name }}" style="height: 150px; object-fit: cover;">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ Str::limit($book->name, 20) }}</h6>
                                        <p class="card-text text-muted">{{ Str::limit($book->author, 15) }}</p>
                                    </div>
                                    <div class="card-footer bg-white border-top-0">
                                        <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-primary w-100">View Details</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="d-grid mt-3">
                        <a href="{{ route('books.index') }}" class="btn btn-outline-primary">Explore More Books</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection