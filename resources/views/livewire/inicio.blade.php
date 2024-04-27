
<div>
    <link rel="stylesheet" href="{{ asset('libs/leaflet/leaflet.css') }}">
    <script src="{{ asset('libs/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('libs/jquery/jquery.min.js') }}"></script>
    <div id="map">
    </div>

    

@push('scripts')
<script>
    document.addEventListener("livewire:load", function () {
    // Create map
        
        osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", { maxZoom: 21, attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap</a> contributors'}),
        google = L.tileLayer("https://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}",{attribution: "google",}),
        map = new L.Map("map", {
            center: new L.LatLng(-40.62030677617555, -72.71502215477118),
            zoom: 4,
            maxZoom: 18
        });

       
        baseMaps = {
            "Ciudades": osm.addTo(map),
            "Satelital": google,
        };


    layerControl = L.control.layers(baseMaps).addTo(map);
    
    });


</script>
@endpush