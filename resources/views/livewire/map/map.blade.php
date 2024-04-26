<section>
    <div wire:ignore wire:init='getLayers' id="map" style="height:60vh;"></div>

    <div class="{{ $toggleModal }} fixed inset-0 overflow-y-auto" style="z-index:2000;" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="modalInfo">
        <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
            <div class="inline-block transform overflow-hidden bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                <form>
                    <div class="bg-white px-4 pb-4">
                        <div class="mt-3">
                            <h3 class="pb-2 text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                {{ __('Draw') }}
                            </h3>
                            <div class="mt-2">
                                <div class="grid grid-cols-1">
                                    <div class="mb-4">
                                        <x-jet-label for="name" value="{{ __('Name') }}" />
                                        <x-jet-input wire:model="tmpName" type="text" name="name" class="mt-1 block w-full" />
                                        <x-jet-input-error for="tmpName" class="mt-2" />
                                    </div>
                                    <div class="mb-4">
                                        <x-jet-label for="description" value="{{ __('Description') }}" />
                                        <textarea wire:model="tmpDescription" name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                        <x-jet-input-error for="description" class="mt-2" />
                                    </div>
                                    <div class="mb-4">
                                        <x-jet-label for="color" value="{{ __('Fill Color') }}" />
                                        <x-jet-input wire:model="tmpFillColor" type="color" name="color" class="mt-1 block w-full" />
                                        <x-jet-input-error for="color" class="mt-2" />
                                    </div>

                                    <div class="mb-4">
                                        <x-jet-label for="color" value="{{ __('Border Color') }}" />
                                        <x-jet-input wire:model="tmpBorderColor" type="color" name="color" class="mt-1 block w-full" />
                                        <x-jet-input-error for="color" class="mt-2" />
                                    </div>
                                    <div class="mb-4">
                                        <x-jet-label for="opacity" value="{{ __('Opacity') }}" />
                                        <x-jet-input wire:model="tmpOpacity" type="range" name="opacity" min="0" max="1" step="0.1" class="mt-1 block w-full" />
                                        <x-jet-input-error for="opacity" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="saveLayer()" class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700 focus:border-gray-900 focus:outline-none focus:ring focus:ring-gray-300 active:bg-gray-900 disabled:opacity-25 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('Save') }}
                        </button>
                        <button wire:click="setToggleModal()" type="button" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium uppercase text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:ml-3 sm:mt-0 sm:w-auto sm:text-sm">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            var layersUrl = '{{ url('storage') }}';

            document.addEventListener("livewire:load", function() {
                // Map
                map = new L.Map("map", {
                    center: new L.LatLng(-40.62030677617555, -72.71502215477118),
                    zoom: 4,
                    maxZoom: 23,
                });
                // Map

                // Layers
                let osmLayer = L.tileLayer(
                    "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                        maxZoom: 23,
                        attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    }
                );
                osmLayer.addTo(map);
                let googleLayer = L.tileLayer(
                    "https://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}", {
                        attribution: "google",
                    }
                );
                osmLayer.addTo(map);
                // Layers

                // Layers Group
                let imageLayerGroup = L.markerClusterGroup();
                let imageLayerGroup_bounds = L.layerGroup();
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

                map.on(L.Draw.Event.CREATED, function(event) {
                    let layer = event.layer;
                    let data = layer.toGeoJSON();
                    if (layer instanceof L.Circle) {
                        data.properties.radius = layer.getRadius();
                    }
                    @this.tmpGeom = JSON.stringify(data);
                    @this.setToggleModal();
                });

                Livewire.on('reload_drawn', data => {
                    data = JSON.parse(data);
                    bounds = L.geoJSON(data).getBounds();
                    boundsArr = [];
                    boundsArr.push([bounds._northEast.lat, bounds._northEast.lng]);
                    boundsArr.push([bounds._southWest.lat, bounds._southWest.lng]);

                    localStorage.mapView = JSON.stringify(boundsArr);
                    window.location.reload();
                })



                // // Object(s) edited - update popups
                // map.on(L.Draw.Event.EDITED, function (event) {
                //     var layers = event.layers;
                // });

                Livewire.on('getLayers', layers => {

                    layers.forEach(layer => {
                        switch (layer.layer_type_id) {
                            case @this.typeSHP:
                                symbology = JSON.parse(layer.symbology);
                                shpLayerGroup.addLayer(L.geoJSON(JSON.parse(layer.geom), {
                                    onEachFeature: (feature, lay) => {
                                        if (typeof feature.properties[symbology
                                                .field] != 'undefined')
                                            lay.bindPopup(
                                                `<p>${feature.properties[symbology.field]}</p>`
                                            )
                                    },
                                    style: symbology
                                })).addTo(map);
                                break;
                            case @this.typeKML:
                                // Load kml file
                                fetch(`${layersUrl}/${layer.file_name}`)
                                    .then(res => res.text())
                                    .then(kmltext => {
                                        // Create new kml overlay
                                        const parser = new DOMParser();
                                        const kml_data = parser.parseFromString(kmltext, 'text/xml');
                                        kmlLayerGroup.addLayer(new L.KML(kml_data)).addTo(map);
                                    });
                                break;
                            case @this.typeTif:
                                //Load Tif
                                if (layer.visible) {
                                    bounds = [];
                                    JSON.parse(layer.geom).forEach(element => {
                                        bounds.push([element[1], element[0]]);
                                    });
                                    bounds = L.latLngBounds(bounds);
                                    tifLayerGroup.addLayer(
                                        L.tileLayer(
                                            `${layersUrl}/tiles/${layer.id}/{z}/{x}/{y}.png`, {
                                                minZoom: 5,
                                                maxZoom: 21,
                                                edgeBufferTiles: 5,
                                                bounds: bounds,
                                                tms: true,
                                                updateWhenIdle: false
                                            }
                                        )).addTo(map);
                                }
                                break;
                            case @this.typeImage:
                            case @this.typeThermo:
                                //Load Image
                                if (layer.data === "[]") {
                                    style = {
                                        color: '#000',
                                        weight: 1,
                                        fillOpacity: 1,
                                        fillColor: '#2abd4c',
                                        radius: 5
                                    }
                                } else {
                                    style = {
                                        color: '#000',
                                        weight: 1,
                                        fillOpacity: 1,
                                        fillColor: '#a61c0a',
                                        radius: 5
                                    }
                                }
                                geom = JSON.parse(layer.geom);
                                imageLayerGroup.addLayer(
                                    L.geoJSON(geom, {
                                        pointToLayer: (feature, latlng) => {
                                            return new L.circleMarker(latlng, style).bindPopup(
                                                `<img src="${layersUrl}/${layer.file_name}" style="margin:0 !important"><p style="margin:0 !important;text-align:center">${layer.name}</p><p  style="margin:0 !important; text-align:center"><a href="map/image/${layer.id}" target="_blank">See more</a></p>`
                                            );
                                        }
                                    })

                                ).addTo(map);
                                imageLayerGroup_bounds.addLayer(
                                    L.geoJSON(geom, {
                                        pointToLayer: (feature, latlng) => {
                                            return new L.circleMarker(latlng, style);
                                        }
                                    })
                                )
                                break;
                            case @this.typeDrawn:
                                if (layer.geom) {
                                    geom = JSON.parse(layer.geom);
                                    if (geom.geometry.type == 'Point') {
                                        if (!geom.properties.radius) {
                                            geom.properties.radius = 5;
                                        }

                                        drawFeatureGroup.addLayer(L.geoJSON(geom, {
                                            pointToLayer: (feature, latlng) => {
                                                options = JSON.parse(layer.symbology);
                                                options.radius = feature.properties
                                                    .radius;
                                                return new L.circle(latlng, options);
                                            }
                                        }).bindPopup(
                                            `<p>${layer.name}</p>`)).addTo(map);


                                    } else {
                                        drawFeatureGroup.addLayer(
                                            L.geoJSON(geom, {
                                                style: JSON.parse(layer.symbology)
                                            }).bindPopup(
                                                `<p>${layer.name}</p>`)
                                        ).addTo(map);
                                    }


                                }
                                break;
                        }
                    });
                    imageLayerGroup.eachLayer(layer => {
                        layer.bringToFront();
                    });

                    setTimeout(() => {
                        var bounds = L.latLngBounds([]);
                        tifLayerGroup.eachLayer(layer => {
                            layer.bringToFront();

                        });
                        kmlLayerGroup.eachLayer(layer => {
                            layer.bringToFront();
                            var layerBounds = layer.getBounds();
                            bounds.extend(layerBounds);
                        });
                        shpLayerGroup.eachLayer(layer => {
                            layer.bringToFront();
                            var layerBounds = layer.getBounds();
                            bounds.extend(layerBounds);
                        });
                        drawFeatureGroup.eachLayer(layer => {
                            layer.bringToFront();
                            var layerBounds = layer.getBounds();
                            bounds.extend(layerBounds);
                        });
                        imageLayerGroup_bounds.eachLayer(layer => {
                            layer.bringToFront();
                            var layerBounds = layer.getBounds();
                            bounds.extend(layerBounds);
                        });

                        if (localStorage.mapView) {
                            map.fitBounds(JSON.parse(localStorage.mapView));
                            localStorage.removeItem('mapView');
                        } else {
                            if (Object.keys(bounds).length) {
                                map.fitBounds(bounds);
                            }
                        }

                    }, 1000);

                })

                // Button Polyline
                let polylineDrawer = new L.Draw.Polyline(map);
                document.querySelector("#btnPolyline").onclick = function(e) {
                    polylineDrawer.enable();

                };
                // Button Polyline

                // Button Polygon
                let polygonDrawer = new L.Draw.Polygon(map);
                document.querySelector("#btnPolygon").onclick = function() {
                    polygonDrawer.enable();
                };
                // Button Polygon

                // Button Rectangle
                let rectangleDrawer = new L.Draw.Rectangle(map);
                document.querySelector("#btnRectangle").onclick = function() {
                    rectangleDrawer.enable();
                };
                // Button Rectangle

                // Button Circle
                let circleDrawer = new L.Draw.Circle(map);
                document.querySelector("#btnCircle").onclick = function() {
                    circleDrawer.enable();
                };
                // Button Circle

                // Button Circle
                let markerDrawer = new L.Draw.Marker(map);
                document.querySelector("#btnMarker").onclick = function() {
                    markerDrawer.enable();
                };
                // Button Circle
            });
        </script>
    @endpush
</section>
