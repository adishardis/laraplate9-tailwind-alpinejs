<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="theme-color" content="#000000" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{ url('notus-js/img/favicon.ico') }}" />
  <link rel="apple-touch-icon" sizes="76x76" href="{{ url('notus-js/img/apple-icon.png') }}" />
    @vite('resources/js/app.js')

  <title>{{ config('app.name') }}</title>
</head>

<body class="text-slate-700 antialiased">
  <div id="root">
    {{-- Sidebar --}}
    @include('super.layouts.partials.sidebar')
    {{-- End Sidebar --}}

    <div class="relative md:ml-64 bg-slate-50">

      {{-- Notifications --}}
      @include('layouts.notif-alert')
      {{-- End Notifications --}}

      {{-- Navbar --}}
      @include('super.layouts.partials.navbar', ['navHeader' => $navHeader ?? ''])
      {{-- End Navbar --}}

      {{-- Content --}}
      <div>
        {{-- Header Page --}}
        <div class="relative bg-pink-600 md:pt-32 pb-32 pt-12">
          <div class="px-4 md:px-10 mx-auto w-full">
            {{ $headerPage ?? '' }}
          </div>
        </div>
        {{-- End Header Page --}}

        <div class="px-4 md:px-10 mx-auto w-full -m-24">
          <div class="flex flex-wrap mt-4">
            {{ $slot }}
          </div>

          {{-- Footer --}}
          @include('super.layouts.partials.footer')
          {{-- End Footer --}}

        </div>
      </div>
      {{-- End Content --}}

    </div>
  </div>

  <script type="text/javascript" src="{{asset('notus-js/js/script.js')}}"></script>
  <script type="text/javascript" src="{{asset('notus-js/vendor/js/popper.js')}}"></script>

  @include('layouts.scripts.alpine-init')

  {{-- End Scripts --}}
</body>

</html>