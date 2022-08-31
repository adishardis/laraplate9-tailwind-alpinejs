@props([
'url' => '',
'id' => '',
'text' => '',
'isTextId' => false
])
<div x-data="{
    url: '{{ $url }}',
    id: '{{ $id }}',
    text: '{{ $text }}',
    isTextId: '{{ $isTextId }}'
}" x-cloak>
    <a :id="id" :href="url + '/' + {{ $id }} + '/edit'" {{ $attributes->merge(['class' => 'text-blue-500 underline',
        'title' => 'Edit'])
        }}>
        {{ $slot }}
    </a>
</div>