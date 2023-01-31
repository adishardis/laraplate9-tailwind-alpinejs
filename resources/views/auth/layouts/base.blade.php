<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#000000" />
    <link rel="shortcut icon" href="{{ url('notus-js/img/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ url('notus-js/img/apple-icon.png') }}" />
    
    <title>{{ config('app.name') }}</title>
        
    @vite('resources/js/app.js')
</head>

<body class="text-slate-700 antialiased">
    {{-- Navbar --}}
    @include('auth.layouts.partials.navbar')
    {{-- End Navbar --}}

    <main>
        <section class="relative w-full h-full py-40 min-h-screen">
            {{ $slot }}
            {{-- Footer --}}
            @include('auth.layouts.partials.footer')
            {{-- End Footer --}}
        </section>
    </main>
</body>
{{-- Scripts --}}

{{-- Vendor --}}
<script src="{{ asset('notus-js/js/script.js') }}"></script>
<script src="{{ asset('notus-js/vendor/js/popper.js') }}"></script>
{{-- End Scripts --}}

</html>