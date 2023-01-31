<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#000000" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ url('notus-js/img/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ url('notus-js/img/apple-icon.png') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite('resources/js/app.js')

    <title>{{ config('app.name') }}</title>
</head>

<body class="text-slate-700 antialiased">
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
    

    {{-- Vendor --}}
    <script src="{{ asset('notus-js/js/script.js') }}"></script>
    <script src="{{ asset('notus-js/vendor/js/popper.js') }}"></script>

    @include('layouts.scripts.alpine-init')

    {{-- End Scripts --}}
</body>

</html>