@props(['title', 'actions'])

<div class="card mb-3">
    <div class="card-header bg-light">{{ $title }}</div>
    <div class="card-body">
        <div class="d-grid gap-2">
            @foreach($actions as $action)
                <a href="{{ $action['url'] ?? '#' }}" class="btn {{ $action['class'] ?? 'btn-outline-primary' }}">{{ $action['label'] }}</a>
            @endforeach
        </div>
    </div>
</div>
