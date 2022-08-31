<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#000000" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ url('dist/notus-js/img/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ url('dist/notus-js/img/apple-icon.png') }}" />
    <link rel="stylesheet" type="text/css" href="{{ mix('dist/notus-js/css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="{{ mix('css/styles.css') }}">

    <title>{{ config('app.name') }}</title>
</head>

<body class="text-blueGray-700 antialiased">
    {{-- Notifications --}}
    @include('layouts.notif-alert')
    {{-- End Notifications --}}

    {{-- Navbar --}}
    @include('layouts.partials.profile-navbar')
    {{-- End Navbar --}}

    {{-- Content --}}
    {{ $slot }}
    {{-- End Content --}}

    {{-- Footer --}}
    @include('layouts.partials.footer')
    {{-- End Footer --}}

    {{-- Scripts --}}
    {{-- Alpine JS --}}
    <script src="{{ mix('js/app.js') }}" defer></script>

    {{-- Mix --}}
    <script src="{{ mix('js/scripts.js') }}" defer></script>

    {{-- Vendor --}}
    <script src="{{ asset('dist/notus-js/js/app.js') }}"></script>
    <script src="{{ asset('dist/notus-js/vendor/js/popper.js') }}"></script>

    @include('layouts.scripts.alpine-init')

    {{-- End Scripts --}}
</body>

</html>