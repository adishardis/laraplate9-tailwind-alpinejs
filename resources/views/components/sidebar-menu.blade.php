@props([
'route',
'icon',
'segmentNo' => null,
'segmentRoute' => ''
])

<li class="items-center">
    <a href="{{ $route ?? '/' }}" class="text-xs uppercase py-3 font-bold block
        {{ ($segmentRoute ? (Request::segment($segmentNo) == $segmentRoute) : (Request::url() == $route)) 
                ? 'text-pink-500 hover:text-pink-600' 
                : 'text-blueGray-700 hover:text-blueGray-500'
        }}">
        <i class="{{ $icon ?? '' }} mr-2 text-sm opacity-75"></i>
        {{ $slot }}
    </a>
</li>