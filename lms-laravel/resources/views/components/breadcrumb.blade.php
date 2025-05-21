<nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 py-3 px-5 rounded mb-4" aria-label="Breadcrumb">
    <ol class="list-reset flex text-gray-700 dark:text-gray-300">
        <li>
            <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-600">Home</a>
        </li>
        @foreach ($breadcrumbs as $breadcrumb)
            <li><span class="mx-2">/</span></li>
            @if ($loop->last)
                <li class="text-gray-500 dark:text-gray-400" aria-current="page">{{ $breadcrumb['label'] }}</li>
            @else
                <li>
                    <a href="{{ $breadcrumb['url'] }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-600">{{ $breadcrumb['label'] }}</a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>
