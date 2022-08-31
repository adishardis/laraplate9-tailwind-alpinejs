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
}">
    <a :id="id" :href="url + '/' + {{ $id }} + '/show'" class="text-blue-500 underline"
        x-text="isTextId ? {{ $id }} : '{{ __('View') }}'">
    </a>
</div>