@props([
'url' => '',
'id' => '',
'text' => '',
])
<div x-data="{
    url: '{{ $url }}',
    id: '{{ $id }}',
    text: '{{ $text }}'
}" x-cloak>
    <a :id="id" x-on:click="deleteAction({{ $id }}, (url + '/' + {{ $id }} + ''))" {{ $attributes->merge(['class' =>
        'cursor-pointer text-red-500 underline', 'title' => 'Delete' ]) }}>
        {{ $slot }}
    </a>
</div>