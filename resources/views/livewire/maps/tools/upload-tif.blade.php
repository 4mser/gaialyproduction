<section>
    <button wire:click="setToggleModal()" class="flex w-16 w-full cursor-pointer flex-col items-center justify-center px-1 py-3 text-center hover:bg-gray-200">
        <img src="img/tif.svg" class="h-6 w-6" />
        <div class="mt-1 text-xs">{{ __('Upload TIF') }}</div>
    </button>

    <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="{{ $toggleModal }} fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
            <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                <form wire:submit.prevent="upload" enctype="multipart/form-data">
                    <div class="bg-white px-4 pb-4">
                        <div class="mt-3">
                            <h3 class="flex items-center pb-4 text-lg text-xl font-medium leading-6 text-gray-900" id="modal-title">
                                <img src="img/tif.svg" class="mr-2 h-6 w-6" />
                                <span> {{ __('Upload TIF') }}</span>
                            </h3>
                            <div class="mt-2">
                                <div class="grid grid-cols-1">
                                    <div class="mb-4">
                                        <x-jet-label for="name" value="{{ __('Name') }}" />
                                        <x-jet-input wire:model="name" type="text" name="name" class="mt-1 block w-full" />
                                        <x-jet-input-error for="name" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-jet-label for="file" value="{{ __('File') }}" />
                                        <x-jet-input wire:model="file" type="file" name="file" id="file-{{ $iteration }}" class="block w-full cursor-pointer rounded-md border border-gray-300 p-1 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                        <x-jet-input-error for="file" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="bg-primary inline-flex w-full items-center justify-center rounded-md border border-transparent px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition focus:outline-none focus:ring focus:ring-gray-300 disabled:opacity-25 sm:ml-3 sm:w-auto sm:text-sm">
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
    <div class="absolute" wire:loading wire:target="setToggleModal,upload,file">
        <livewire:components.loading>
    </div>
    <script>
        document.addEventListener("livewire:load", function() {
            Livewire.on('reload_tif', data => {
                bounds = [];
                JSON.parse(data).forEach(element => {
                    bounds.push([element[1], element[0]]);
                });
                bounds = L.latLngBounds(bounds);
                boundsArr = [];
                boundsArr.push([bounds._northEast.lat, bounds._northEast.lng]);
                boundsArr.push([bounds._southWest.lat, bounds._southWest.lng]);

                localStorage.mapView = JSON.stringify(boundsArr);
                window.location.reload();
            });
        });
    </script>
</section>
