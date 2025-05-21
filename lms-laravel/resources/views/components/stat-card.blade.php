@props(['title', 'value', 'description', 'bgClass' => 'bg-primary'])

<div class="card text-white {{ $bgClass }} mb-3">
    <div class="card-header">{{ $title }}</div>
    <div class="card-body">
        <h5 class="card-title">{{ $value }}</h5>
        <p class="card-text">{{ $description }}</p>
    </div>
</div>
