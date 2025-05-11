@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Shopping Cart</h1>
    
    @if($cartItems->count() > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Cart Items ({{ $cartItems->count() }})</h5>
                        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear your cart?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash me-1"></i> Clear Cart
                            </button>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @foreach($cartItems as $item)
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-md-2 col-4 mb-2 mb-md-0">
                                            <img src="{{ $item->book->cover_image_url }}" alt="{{ $item->book->name }}" class="img-fluid rounded cart-item-img">
                                        </div>
                                        <div class="col-md-6 col-8 mb-2 mb-md-0">
                                            <h5 class="mb-1">{{ $item->book->name }}</h5>
                                            <p class="mb-1 text-muted">{{ $item->book->author }}</p>
                                            <span class="badge bg-primary">{{ $item->book->category }}</span>
                                        </div>
                                        <div class="col-md-2 col-6 text-md-center">
                                            <h6 class="mb-0">${{ number_format($item->book->price, 2) }}</h6>
                                        </div>
                                        <div class="col-md-2 col-6 text-end">
                                            <form action="{{ route('cart.remove', $item) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i> Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('books.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                    </a>
                    <a href="{{ route('cart.checkout') }}" class="btn btn-primary">
                        Proceed to Checkout <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <span>${{ number_format($totalPrice, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Tax</span>
                            <span>${{ number_format($totalPrice * 0.1, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3 fw-bold">
                            <span>Total</span>
                            <span>${{ number_format($totalPrice * 1.1, 2) }}</span>
                        </div>
                        
                        <div class="d-grid mt-4">
                            <a href="{{ route('cart.checkout') }}" class="btn btn-success">
                                <i class="fas fa-credit-card me-2"></i> Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Have a Coupon?</h5>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Coupon code">
                                <button class="btn btn-outline-primary" type="button">Apply</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                <h3>Your cart is empty</h3>
                <p class="mb-4">Looks like you haven't added any books to your cart yet.</p>
                <a href="{{ route('books.index') }}" class="btn btn-primary">
                    <i class="fas fa-book me-2"></i> Browse Books
                </a>
            </div>
        </div>
    @endif
</div>
@endsection