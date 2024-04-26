<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    {{--
    <link rel="stylesheet" href="toastr.css" /> --}}

    <link rel="stylesheet" href="{{ asset('libs/leaflet/leaflet.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/leaflet-draw/dist/leaflet.draw.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/markerCluster/MarkerCluster.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/markerCluster/MarkerCluster.Default.css') }}">


    @livewireStyles

    @stack('styles')

</head>

<body class="font-sans antialiased">

    <div class="relative min-h-screen md:flex">

        <!-- mobile menu bar -->
        <div class="bg-grey-900 text-grey flex justify-between md:hidden">
            <!-- logo -->
            <a href="#" class="block p-4 font-bold text-black">
                <x-jet-application-mark class="block" />
            </a>

            <!-- mobile menu button -->
            <button class="mobile-menu-button focus:bg-grey-900 p-4 focus:outline-none">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- sidebar -->
        @livewire('navigation-menu')

        <!-- content -->
        <div class="h-screen flex-1 overflow-auto bg-gray-100">
            <x-alert-free-trial />
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
    <script src="{{ asset('libs/leaflet-draw/dist/leaflet.draw.js') }}"></script>
    <script src="{{ asset('libs/leaflet-kml/L.KML.js') }}"></script>
    <script src="{{ asset('libs/markerCluster/leaflet.markercluster.js') }}"></script>
    {{-- <script src="{{ asset('libs/georaster/dist/georaster.bundle.min.js') }}"></script>
    <script src="{{ asset('libs/georaster-layer-for-leaflet/georaster-layer-for-leaflet.bundle.js') }}"></script> --}}
    <script src="https://unpkg.com/georaster"></script>
    <script src="https://unpkg.com/proj4"></script>
    <script src="https://unpkg.com/georaster-layer-for-leaflet"></script>


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
