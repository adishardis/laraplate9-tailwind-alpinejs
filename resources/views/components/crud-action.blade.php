@props([
'isShow' => false,
'textShow' => '',
'isEdit' => false,
'textEdit' => '',
'isDelete' => false,
'textDelete' => '',
'url' => '',
'id' => '',
])

@if ($isShow)
<x-show-action url='{{ $url }}' id='{{ $id }}' text='{{ $textShow }}' />
@endif

@if ($isEdit)
<x-edit-action url='{{ $url }}' id='{{ $id }}' text='{{ $textEdit }}' />
@endif

@if ($isDelete)
<x-delete-action url='{{ $url }}' id='{{ $id }}' text='{{ $textDelete }}' />
@endif