<section>
    <button wire:click="setToggleModal()" class="flex w-16 w-full cursor-pointer flex-col items-center justify-center px-1 py-3 text-center hover:bg-gray-200">
        <img src="img/thermal.svg" class="h-6 w-6" />
        <div class="mt-1 text-xs">{{ __('Upload Thermal Image') }}</div>
    </button>

    <div style="z-index:2000;" class="{{ $toggleModal }} fixed inset-0 z-auto overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-800 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
            <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                <form wire:submit.prevent="upload" enctype="multipart/form-data">
                    <div class="bg-white px-4 pb-4">
                        <div class="mt-3">
                            <h3 class="flex items-center pb-4 text-xl font-medium leading-6 text-gray-900" id="modal-title">
                                <img src="img/image.svg" class="mr-2 h-6 w-6" />
                                <span>{{ __('Upload thermal image') }}</span>
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
                                        <x-jet-input wire:model="file" type="file" name="file" accept="png,jpg,jpeg" id="file-{{ $iteration }}" class="mb-2 block w-full cursor-pointer rounded-md border border-gray-300 p-1 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                        <x-jet-input-error for="file" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-jet-label for="pallete" value="{{ __('Pallete') }}" class="mt-2" />
                                        <select id="pallete" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="pallete" wire:model="pallete">
                                            @foreach ($palletes as $p)
                                                <option value="{{ $p['code'] }}">{{ $p['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <x-jet-label for="colors" value="{{ __('Colors') }}" class="mt-4" />
                                        <div class="mt-1 block w-full overflow-hidden rounded-md border border-gray-300 shadow-sm">
                                            <img class="h-9" src="{{ url('img/palettes/' . $pallete . '.png') }}" class="mt-3 w-full" />
                                        </div>
                                    </div>
                                    <div>
                                        <x-jet-label for="camera_models_support" value="{{ __('DJI Camera models supported') }}" class="mt-4" />
                                        @foreach ($cameraModelsSupported as $item)
                                            <div class="inline-flex items-center justify-center rounded border border-gray-800 bg-gray-100 px-2 py-1 text-xs leading-none text-gray-800">{{ $item }}</div>
                                        @endforeach
                                    </div>
                                    @if ($models)
                                        <div class="pt-4">
                                            <livewire:users.available-balance />
                                            <p class="mb-3 mt-6 border-b-2">{{ __('Process the image with AI model') }}</p>
                                            <style>
                                                /* CHECKBOX TOGGLE SWITCH */
                                                /* @apply rules for documentation, these do not work as inline style */
                                                .toggle-checkbox:checked {
                                                    @apply: right-0 border-green-400;
                                                    right: 0;
                                                    border-color: #68D391;
                                                }

                                                .toggle-checkbox:checked+.toggle-label {
                                                    @apply: bg-green-400;
                                                    background-color: #68D391;
                                                }
                                            </style>
                                            {{-- div with grid style 2 columns centered with tailwindcss --}}
                                            <div class="grid grid-cols-1 gap-4 text-center">
                                                @foreach ($models as $model)
                                                    <div wire:click="setModel('{{ $model['id'] }}')">
                                                        <label for="toggle{{ $model['id'] }}">{{ $model['name'] }} ({{ $model['price'] }} credits each) </label>
                                                        <div class="relative ml-2 inline-block w-10 select-none align-middle transition duration-200 ease-in">
                                                            <input type="checkbox" name="toggle{{ $model['id'] }}" id="toggle{{ $model['id'] }}" {{ $model['status'] ? 'checked' : '' }} class="toggle-checkbox absolute block h-6 w-6 cursor-pointer appearance-none rounded-full border-4 bg-white" />
                                                            <label for="toggle" class="toggle-label block h-6 cursor-pointer overflow-hidden rounded-full bg-gray-300"></label>
                                                        </div>
                                                        {{-- question mark svg  --}}
                                                        <span class="inline-block align-middle" title="{{ $model['description'] }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM8.94 6.94a.75.75 0 11-1.061-1.061 3 3 0 112.871 5.026v.345a.75.75 0 01-1.5 0v-.5c0-.72.57-1.172 1.081-1.287A1.5 1.5 0 108.94 6.94zM10 15a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if (!$hasCoordinates)
                                        <div class="my-5 rounded-md border border-yellow-300 bg-yellow-100 p-2 text-sm">
                                            {{ __('The coordinates must be set using EPSG: 4326 (WGS84) format.') }}'
                                        </div>
                                        <div>
                                            <x-jet-label for="lat" value="{{ __('Latitude') }}" />
                                            <x-jet-input wire:model="lat" type="text" name="lat" class="mb-2 mt-1 block w-full" />
                                            <x-jet-input-error for="latitude" class="mt-2" />
                                        </div>
                                        <div>
                                            <x-jet-label for="lng" value="{{ __('Longitude') }}" />
                                            <x-jet-input wire:model="lng" type="text" name="lng" class="mt-1 block w-full" />
                                            <x-jet-input-error for="lnge" class="mt-2" />
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="bg-primary inline-flex w-full items-center justify-center rounded-md border border-transparent px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700 focus:border-gray-900 focus:outline-none focus:ring focus:ring-gray-300 active:bg-gray-900 disabled:opacity-25 sm:ml-3 sm:w-auto sm:text-sm">
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
            Livewire.on('reload_img', () => {
                // const reload = () => {
                bounds = map.getBounds();

                boundsArr = [];
                boundsArr.push([bounds._northEast.lat, bounds._northEast.lng]);
                boundsArr.push([bounds._southWest.lat, bounds._southWest.lng]);

                localStorage.mapView = JSON.stringify(boundsArr);
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
                // }
            });
        });
    </script>
</section>
