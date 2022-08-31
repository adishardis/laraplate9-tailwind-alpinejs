@props(['url', 'text'])

<a href="{{ $url }}" class="text-white text-sm uppercase hidden lg:inline-block font-semibold">
    {{ $text ?? __('Dashboard') }}
</a>