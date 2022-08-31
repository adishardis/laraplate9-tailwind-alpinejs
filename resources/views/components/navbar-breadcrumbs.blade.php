@props(['items'])

@foreach ($items as $item)
@if (!$loop->first)
&nbsp;&nbsp;/&nbsp;&nbsp;
@endif
<x-navbar-header :url="$item['url']" :text="$item['text']" />
@endforeach