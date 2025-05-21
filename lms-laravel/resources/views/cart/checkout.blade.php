@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-4 fade-in">
    <h1>Checkout</h1>
    @if(count($cartItems) > 0)
        <form action="{{ route('payments.process') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Books</label>
                <ul class="list-group mb-2">
                    @foreach($cartItems as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $item->name }}
                            <span class="badge bg-primary rounded-pill">{{ $item->book_quantity }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="mb-3">
                <strong>Total Price: </strong> {{ $totalPrice }}
            </div>
            <button type="submit" class="btn btn-success">Pay Now</button>
        </form>
    @else
        <div class="alert alert-info">Your cart is empty.</div>
    @endif
</div>
@endsection
