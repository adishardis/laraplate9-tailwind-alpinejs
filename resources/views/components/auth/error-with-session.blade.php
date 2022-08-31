@if (session('error'))
<div {{ $attributes->merge(['class' => 'text-red-500']) }} role="alert">
    <span class="fa fa-exclamation-triangle" aria-hidden="true"></span>
    {{ session('error')}}
</div>
@endif