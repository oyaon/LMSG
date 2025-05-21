@extends('layouts.app')

@section('title', 'All Authors')

@section('content')
<div class="container py-4 fade-in">
    <h1>All Authors</h1>
    <div class="row g-3">
        @forelse($authors as $author)
            <div class="col-sm-6 col-md-4 col-lg-3">
                <x-author-card :author="$author" />
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No authors found.</div>
            </div>
        @endforelse
    </div>
    @if(method_exists($authors, 'links'))
        <div class="mt-4 d-flex justify-content-center">
            {{ $authors->links() }}
        </div>
    @endif
</div>
@endsection
