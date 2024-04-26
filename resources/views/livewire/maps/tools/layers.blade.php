<section>
    <button wire:click="setToggleModal()" class="flex w-16 w-full cursor-pointer flex-col items-center justify-center px-1 py-3 text-center hover:bg-gray-200">
        <img src="img/layer.svg" class="h-6 w-6" />
        <div class="mt-1 text-xs">{{ __('Layers') }}</div>
    </button>
    <!-- modal principal -->
    <div style="z-index:2000;" class="{{ $toggleModal }} fixed inset-0 z-auto overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
            <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl sm:align-middle">
                <div class="bg-white px-4 pb-4">
                    <h3 class="mt-3 pb-2 text-center text-lg font-medium leading-6 text-gray-900" id="modal-title">
                        {{ __('Layers') }}
                    </h3>
                    <div class="max-h-80 overflow-auto">
                        <div class="overflow-hidden" style="width:100%;">
                            <!-- This is an example component -->
                            <div class="mx-auto max-w-2xl">
                                <div class="border-b border-gray-200 dark:border-gray-700">
                                    <ul class="-mb-px flex flex-wrap" id="myTab" data-tabs-toggle="#tabContent" role="tablist">
                                        <li class="mr-2" srole="presentation">
                                            <button wire:click="setTab({{ $typeImage }})" class="@if ($tab == $typeImage) active @endif inline-block rounded-t-lg border-b-2 border-transparent px-4 py-4 text-center text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300" id="images-tab" data-tabs-target="#images" type="button" role="tab" aria-controls="profile" aria-selected="true">{{ __('Images') }}</button>
                                        </li>
                                        <li class="mr-2" role="presentation">
                                            <button wire:click="setTab({{ $typeKml }})" class="@if ($tab == $typeKml) active @endif inline-block rounded-t-lg border-b-2 border-transparent px-4 py-4 text-center text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300" id="kml-tab" data-tabs-target="#kml" type="button" role="tab" aria-controls="kml" aria-selected="false">{{ __('KML/KMZ') }}</button>
                                        </li>
                                        <li class="mr-2" role="presentation">
                                            <button wire:click="setTab({{ $typeTif }})" class="@if ($tab == $typeTif) active @endif inline-block rounded-t-lg border-b-2 border-transparent px-4 py-4 text-center text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300" id="tif-tab" data-tabs-target="#tif" type="button" role="tab" aria-controls="tif" aria-selected="false">{{ __('TIF') }}</button>
                                        </li>
                                        <li role="presentation">
                                            <button wire:click="setTab({{ $typeShp }})" class="@if ($tab == $typeShp) active @endif inline-block rounded-t-lg border-b-2 border-transparent px-4 py-4 text-center text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300" id="shapefiles-tab" data-tabs-target="#shapefiles" type="button" role="tab" aria-controls="shapefiles" aria-selected="false">{{ __('Shapefiles') }}</button>
                                        </li>
                                        <li role="presentation">
                                            <button wire:click="setTab({{ $typeDrawn }})" class="@if ($tab == $typeDrawn) active @endif inline-block rounded-t-lg border-b-2 border-transparent px-4 py-4 text-center text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300" id="drawn-tab" data-tabs-target="#drawn" type="button" role="tab" aria-controls="drawn" aria-selected="false">{{ __('Drawn') }}</button>
                                        </li>
                                    </ul>
                                </div>
                                <div id="tabContent">
                                    <div class="@if ($tab !== $typeImage) hidden @endif rounded-lg" id="images" role="tabpanel" aria-labelledby="images-tab">
                                        @include('livewire.maps.tools.layer-list-table', [
                                            'items' => $layers[$typeImage],
                                        ])
                                    </div>
                                    <div class="@if ($tab !== $typeKml) hidden @endif rounded-lg" id="kml" role="tabpanel" aria-labelledby="kml-tab">
                                        @include('livewire.maps.tools.layer-list-table', [
                                            'items' => $layers[$typeKml],
                                        ])
                                    </div>
                                    <div class="@if ($tab !== $typeTif) hidden @endif rounded-lg" id="tif" role="tabpanel" aria-labelledby="tif-tab">
                                        @include('livewire.maps.tools.layer-list-table', [
                                            'items' => $layers[$typeTif],
                                        ])

                                    </div>
                                    <div class="@if ($tab !== $typeShp) hidden @endif rounded-lg" id="shapefiles" role="tabpanel" aria-labelledby="shapefiles-tab">
                                        @include('livewire.maps.tools.layer-list-table', [
                                            'items' => $layers[$typeShp],
                                        ])
                                    </div>
                                    <div class="@if ($tab !== $typeDrawn) hidden @endif rounded-lg" id="drawn" role="tabpanel" aria-labelledby="drawn-tab">
                                        @include('livewire.maps.tools.layer-list-table', [
                                            'items' => $layers[$typeDrawn],
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse">
                    <button wire:click="setToggleModal()" type="button" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium uppercase text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:ml-3 sm:mt-0 sm:w-auto sm:text-sm">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="absolute" style="z-index:2000" wire:loading>
        <livewire:components.loading>
    </div>
    <script>
        document.addEventListener("livewire:load", function() {
            Livewire.on('reload_map', () => {
                bounds = map.getBounds();

                boundsArr = [];
                boundsArr.push([bounds._northEast.lat, bounds._northEast.lng]);
                boundsArr.push([bounds._southWest.lat, bounds._southWest.lng]);

                localStorage.mapView = JSON.stringify(boundsArr);
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            })
        });
    </script>
</section>
