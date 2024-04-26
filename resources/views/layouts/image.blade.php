<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <meta name="csrf-token"
          content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet"
          href="http://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet"
          href="http://pro.fontawesome.com/releases/v5.10.0/css/all.css"
          integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p"
          crossorigin="anonymous" />
    <!-- Styles -->
    <link rel="stylesheet"
          href="{{ mix('css/app.css') }}">
    <link rel="stylesheet"
          href="{{ asset('css/styles.css') }}">
    {{--
    <link rel="stylesheet" href="toastr.css" /> --}}

    <link rel="stylesheet"
          href="{{ asset('libs/leaflet/leaflet.css') }}">
    <link rel="stylesheet"
          href="{{ asset('libs/geoman/leaflet-geoman.css') }}">
    <link rel="stylesheet"
          href="{{ asset('libs/leaflet-mouse-position/src/L.Control.MousePosition.css') }}">


    @livewireStyles

    @stack('styles')
    @stack('modals')

</head>

<body class="font-sans antialiased">

    <div class="relative min-h-screen md:flex">

        <!-- mobile menu bar -->
        <div class="bg-grey-900 text-grey flex justify-between md:hidden">
            <!-- logo -->
            <a href="#"
               class="block p-4 font-bold text-black">
                <x-jet-application-mark class="block" />
            </a>

            <!-- mobile menu button -->
            <button class="mobile-menu-button focus:bg-grey-900 p-4 focus:outline-none">
                <svg class="h-5 w-5"
                     xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- content -->
        <div class="h-screen flex-1 overflow-auto bg-gray-100">
            {{ $slot }}
        </div>

    </div>

    @stack('modals')

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ url('js/swal.js') }}"></script>

    @livewireScripts

    @stack('scripts')

    <script src="{{ asset('libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('libs/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('libs/geoman/leaflet-geoman.min.js') }}"></script>
    <script src="{{ asset('libs/leaflet-kml/L.KML.js') }}"></script>
    <script src="{{ asset('libs/leaflet-mouse-position/src/L.Control.MousePosition.js') }}"></script>

    <script>
        @if (session('success'))
            toast("success", "{{ session('success') }}");
        @elseif (session('error'))
            toast("error", "{{ session('error') }}");
        @endif

        document.addEventListener("livewire:load", function() {
            Livewire.on('showLoading', (message) => {
                showLoading(message);
            })
        });

        document.addEventListener("livewire:load", function() {
            Livewire.on('closeLoading', () => {
                closeLoading();
            })
        });


        // Navigation Menu
        // grab everything we need
        const btn = document.querySelector(".mobile-menu-button");
        const sidebar = document.querySelector(".sidebar");

        // add our event listener for the click
        btn.addEventListener("click", () => {
            sidebar.classList.toggle("-translate-x-full");
        });
        // Navigation Menu
    </script>

</body>

</html>
