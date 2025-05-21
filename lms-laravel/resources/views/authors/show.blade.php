@extends('layouts.app')

@section('title', $author->name)

@section('content')
<div class="container py-4 fade-in">
    <div class="row">
        <div class="col-md-3 text-center">
            <img src="{{ $author->profile_image ? asset($author->profile_image) : asset('images/author-placeholder.png') }}" class="rounded-circle mb-3" alt="{{ $author->name }}" width="120" height="120">
        </div>
        <div class="col-md-9">
            <h1>{{ $author->name }}</h1>
            @if($author->bio)
                <p class="text-muted">{{ $author->bio }}</p>
            @endif
            <hr>
            <h4>Books by {{ $author->name }}</h4>
            <div class="row g-3">
                @forelse($author->books as $book)
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <x-book-card :book="$book" />
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">No books found for this author.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
