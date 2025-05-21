@props(['author'])

<div class="card h-100 shadow-sm">
    <div class="card-body d-flex flex-column align-items-center">
        <img src="{{ $author->profile_image ? asset($author->profile_image) : asset('images/author-placeholder.png') }}" class="rounded-circle mb-3" alt="{{ $author->name }}" width="80" height="80">
        <h5 class="card-title text-center">{{ $author->name }}</h5>
        <a href="{{ route('authors.show', $author->id) }}" class="btn btn-outline-primary mt-auto">View Details</a>
    </div>
</div>
