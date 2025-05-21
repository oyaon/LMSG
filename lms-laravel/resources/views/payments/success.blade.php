@extends('layouts.app')

@section('title', 'Payment Success')

@section('content')
<div class="container py-4 fade-in">
    <h1>Payment Successful</h1>
    <p>Your payment was processed successfully. Thank you!</p>
    <a href="{{ route('books.index') }}" class="btn btn-primary mt-3">Continue Browsing Books</a>
</div>
@endsection
