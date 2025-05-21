@props(['title', 'items', 'emptyMessage'])

<div class="card mb-3">
    <div class="card-header bg-light">{{ $title }}</div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            @forelse($items as $item)
                {{ $slot }}
            @empty
                <li class="list-group-item">{{ $emptyMessage }}</li>
            @endforelse
        </ul>
    </div>
</div>
