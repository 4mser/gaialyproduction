document.addEventListener("livewire:load", function () {
    // Map
    map = new L.Map("map", {
        center: new L.LatLng(-40.62030677617555, -72.71502215477118),
        zoom: 4,
        maxZoom: 18,
    });
    // Map

    // Layers
    let osmLayer = L.tileLayer(
        "http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
        {
            maxZoom: 21,
            attribution:
                '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }
    );
    osmLayer.addTo(map);
    let googleLayer = L.tileLayer(
        "http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}",
        {
            attribution: "google",
        }
    );
    osmLayer.addTo(map);
    // Layers

    // Layers Group
    let imageLayerGroup = L.layerGroup();
    let shpLayerGroup = L.layerGroup();
    let kmlLayerGroup = L.layerGroup();
    let tifLayerGroup = L.layerGroup();
    let drawFeatureGroup = L.featureGroup();
    drawFeatureGroup.addTo(map);

    // Base Maps
    let baseMaps = {
        Cities: osmLayer,
        Satellite: googleLayer,
    };
    // Base Maps

    // Overlay Maps
    let overlayMaps = {
        Images: imageLayerGroup,
        SHP: shpLayerGroup,
        KML: kmlLayerGroup,
        TIF: tifLayerGroup,
        Draw: drawFeatureGroup,
    };
    // Overlay Maps

    L.control.layers(baseMaps, overlayMaps).addTo(map);

    let drawnItems = new L.FeatureGroup();

    map.addLayer(drawnItems);

    let drawControl = new L.Control.Draw({
        // position: "topright",
        draw: {
            polyline: false,
            polygon: false,
            rectangle: false,
            circle: false,
            marker: false,
            circlemarker: false,
        },
        edit: {
            featureGroup: drawnItems,
        },
    });
    map.addControl(drawControl);

    map.on(L.Draw.Event.CREATED, function (event) {
        let layer = event.layer;
        let data = layer.toGeoJSON();
        console.log(layer);
        console.log(data);
        if (layer instanceof L.Circle) {
            data.properties.radius = layer.getRadius();
        }
        // @this.tmpGeom = JSON.stringify(data);
        @this.setToggleModal();
        // toggleModal();
    });

    // Button Polyline
    let polylineDrawer = new L.Draw.Polyline(map);
    document.querySelector("#btnPolyline").onclick = function (e) {
        polylineDrawer.enable();
        
    };
    // Button Polyline

    // Button Polygon
    let polygonDrawer = new L.Draw.Polygon(map);
    document.querySelector("#btnPolygon").onclick = function () {
        polygonDrawer.enable();
    };
    // Button Polygon

    // Button Rectangle
    let rectangleDrawer = new L.Draw.Rectangle(map);
    document.querySelector("#btnRectangle").onclick = function () {
        rectangleDrawer.enable();
    };
    // Button Rectangle

    // Button Circle
    let circleDrawer = new L.Draw.Circle(map);
    document.querySelector("#btnCircle").onclick = function () {
        circleDrawer.enable();
    };
    // Button Circle

    // Button Circle
    let markerDrawer = new L.Draw.Marker(map);
    document.querySelector("#btnMarker").onclick = function () {
        markerDrawer.enable();
    };
    // Button Circle
});
