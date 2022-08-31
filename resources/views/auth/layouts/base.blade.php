<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#000000" />
    <link rel="shortcut icon" href="{{ url('dist/notus-js/img/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ url('dist/notus-js/img/apple-icon.png') }}" />
    <link rel="stylesheet" type="text/css" href="{{ mix('dist/notus-js/css/styles.css') }}">
    <title>{{ config('app.name') }}</title>
</head>

<body class="text-blueGray-700 antialiased">
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
{{-- Alpine JS --}}
<script src="{{ mix('js/app.js') }}" defer></script>

{{-- Vendor --}}
<script src="{{ asset('dist/notus-js/js/app.js') }}"></script>
<script src="{{ asset('dist/notus-js/vendor/js/popper.js') }}"></script>
{{-- End Scripts --}}

</html>