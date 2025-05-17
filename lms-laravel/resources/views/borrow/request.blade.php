@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2>Borrow Book</h2>
    <form action="{{ route('borrow.request', $book->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="book_name" class="form-label">Book</label>
            <input type="text" class="form-control" id="book_name" value="{{ $book->name }}" disabled>
        </div>
        <button type="submit" class="btn btn-primary">Request Borrow</button>
    </form>
</div>
@endsection
