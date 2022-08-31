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
  <link rel="stylesheet" type="text/css" href="{{ mix('css/styles.css') }}">

  <title>{{ config('app.name') }}</title>
</head>

<body class="text-blueGray-700 antialiased">
  <div id="root">
    {{-- Sidebar --}}
    @include('super.layouts.partials.sidebar')
    {{-- End Sidebar --}}

    <div class="relative md:ml-64 bg-blueGray-50">

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

  {{-- Scripts --}}
  {{-- Alpine JS --}}
  <script src="{{ mix('js/app.js') }}" defer></script>

  {{-- Mix --}}
  <script src="{{ mix('js/scripts.js') }}" defer></script>

  {{-- Vendor --}}
  <script src="{{ mix('dist/notus-js/js/app.js') }}"></script>
  <script src="{{ mix('dist/notus-js/vendor/js/popper.js') }}"></script>

  {{-- dropzone --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.js"
    integrity="sha512-VQQXLthlZQO00P+uEu4mJ4G4OAgqTtKG1hri56kQY1DtdLeIqhKUp9W/lllDDu3uN3SnUNawpW7lBda8+dSi7w=="
    crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/dropzone.min.css"
    integrity="sha512-jU/7UFiaW5UBGODEopEqnbIAHOI8fO6T99m7Tsmqs2gkdujByJfkCbbfPSN4Wlqlb9TGnsuC0YgUgWkRBK7B9A=="
    crossorigin="anonymous" />

  @include('layouts.scripts.alpine-init')

  {{-- End Scripts --}}
</body>

</html>