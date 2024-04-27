<section class="flex h-screen flex-col">
    <div class="m-2 h-screen rounded-md border-gray-300 bg-white shadow-md">
        <div class="flex flex-auto flex-col" style="height:65%;">
            <div class="flex justify-between p-2">
                <div class="inline-flex h-12 w-4/5 rounded border bg-transparent">
                    <div class="relative mb-6 flex h-full w-full flex-wrap items-stretch">
                        <div class="flex">
                            <span class="whitespace-no-wrap text-grey-dark flex items-center rounded rounded-r-none border border-r-0 border-none bg-transparent py-2 text-sm leading-normal lg:px-6">
                                <svg width="18" height="18" class="w-4 lg:w-auto" viewBox="0 0 18 18" fill="none" xmlns="https://www.w3.org/2000/svg">
                                    <path d="M8.11086 15.2217C12.0381 15.2217 15.2217 12.0381 15.2217 8.11086C15.2217 4.18364 12.0381 1 8.11086 1C4.18364 1 1 4.18364 1 8.11086C1 12.0381 4.18364 15.2217 8.11086 15.2217Z" stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M16.9993 16.9993L13.1328 13.1328" stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </div>
                        <input wire:model="search" type="text" name="search" class="text-xxs lg:px- relative w-1/5  flex-1 flex-shrink flex-grow rounded rounded-l-none border border-l-0 border-none px-3 font-thin leading-normal tracking-wide text-gray-500 focus:outline-none lg:text-xs" placeholder="{{ __('Search') }}" />
                    </div>
                </div>
                <select wire:model="operationTypeId" class="order-3 mb-2 block w-full rounded-md border-gray-300 text-xs shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:order-4 sm:ml-2 sm:mb-0" name="operation_type_id">
                    <option value="">{{ __('All inspections by type') }}</option>
                    @foreach ($operationTypeOptions as $key => $operationType)
                        <option value="{{ $key }}">{{ $operationType }}</option>
                    @endforeach
                </select>
                @if (auth()->user()->isSuperAdminProfile() ||
                        auth()->user()->isOwnerProfile())
                    <select wire:model="companyId" class="order-1 block w-full rounded-md border-gray-300 text-xs shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:order-3 sm:ml-2 sm:mb-0" name="company_id">
                        <option value="">{{ __('All companies') }}</option>
                        @foreach ($companyOptions as $key => $name)
                            <option value="{{ $key }}">{{ $name }}</option>
                        @endforeach
                    </select>
                @endif
                <a href="{{ route('inspections.form') }}" title="{{ __('Create inspection') }}" class="bg-primary focus:shadow-outline order-4 float-right ml-2 inline-flex items-center rounded py-2 px-4 text-xs font-bold text-black shadow hover:bg-blue-300 focus:outline-none">
                    <svg class="-ml-1 mr-1 h-4" xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    {{ __('Create') }}
                </a>
            </div>
            <div class="flex-auto" style="background-color:#AAD3DF;">
                <link rel="stylesheet" href="{{ asset('libs/leaflet/leaflet.css') }}">
                <script src="{{ asset('libs/leaflet/leaflet.js') }}"></script>
                <script src="{{ asset('libs/jquery/jquery.min.js') }}"></script>
                <div wire:ignore id="map" class="operation h-full w-full"></div>
            </div>
        </div>
        <div class="flex-auto overflow-hidden overflow-y-auto" style="height:35%;">
            @if ($operations->count())
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-primary border-b border-gray-200 text-left leading-4 tracking-wider">
                            <th class="px-3 py-4">{{ __('Inspection') }}</th>
                            <th class="px-3 py-4">{{ __('Description') }}</th>
                            <th class="px-3 py-4">{{ __('Inspection Type') }}</th>
                            @if (auth()->user()->isSuperAdminProfile() ||
                                    auth()->user()->isOwnerProfile())
                                <th class="px-3 py-4">{{ __('Company') }}</th>
                            @endif
                            <th class="px-3 py-4">{{ __('Layers') }}</th>
                            <th class="border-b border-gray-200 px-3 py-4 text-right">
                                <livewire:operations.demo-button />
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($operations as $operation)
                            <tr class="whitespace-no-wrap border-b border-gray-200">
                                <td class="px-3 py-2">{{ $operation->name }}</td>
                                <td class="px-3 py-2">{{ $operation->description }}</td>
                                <td class="px-3 py-2">{{ $operation->operation_type_name }}</td>
                                @if (auth()->user()->isSuperAdminProfile() ||
                                        auth()->user()->isOwnerProfile())
                                    <td class="px-3 py-2">{{ $operation->company_name }}</td>
                                @endif
                                <td class="px-3 py-2">{{ $operation->layers->count() }}</td>
                                <td class="px-6 py-4 text-right text-xs">
                                    <a href="{{ route('inspections.form', ['id' => $operation->id]) }}" class="mb-1 ml-1 inline-block cursor-pointer rounded border border-blue-500 px-2 py-1 text-blue-500 transition duration-300 hover:bg-blue-700 hover:text-white focus:outline-none">
                                        {{ __('Edit') }}
                                    </a>
                                    <a wire:click="showMap({{ $operation->id }})" title="{{ __('show map') }}" class="mb-1 ml-1 inline-block cursor-pointer rounded border border-blue-500 px-2 py-1 text-blue-500 transition duration-300 hover:bg-blue-700 hover:text-white focus:outline-none">
                                        {{ __('Map') }}
                                    </a>
                                    <a target="_blank" href="{{ route('map.report', ['operationId' => $operation->id]) }}" title="{{ __('Create report') }}" class="mb-1 ml-1 inline-block cursor-pointer rounded border border-blue-500 px-2 py-1 text-blue-500 transition duration-300 hover:bg-blue-700 hover:text-white focus:outline-none">
                                        {{ __('Report') }}
                                    </a>
                                    <a onClick="confirm('{{ $operation->id }}','{{ __('Are you sure you want to delete this operation?') }}','deleteOperation')" class="mb-1 ml-1 inline-block cursor-pointer rounded border border-red-500 px-2 py-1 text-red-500 transition duration-300 hover:bg-red-700 hover:text-white focus:outline-none">
                                        {{ __('Delete') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="border-t border-gray-200 bg-white px-1 py-2 sm:px-3">
                    {{ $operations->links() }}
                </div>
            @else
                <div class="text-right mt-3 mr-3">
                    <livewire:operations.demo-button />
                </div>
                <div class="relative mx-auto mt-6 rounded py-3 text-center text-gray-700" role="alert">
                    <p><strong class="font-bold">{{ __('No search results') }}</strong>
                    </p>
                    <span class="block sm:inline">{{ __('Nothing to show here') }}</span>
                </div>
            @endif
        </div>
    </div>


    @push('scripts')
        <script>
            document.addEventListener("livewire:load", function() {
                // Create map

                osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                        maxZoom: 21,
                        attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }),
                    google = L.tileLayer("https://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}", {
                        attribution: "google",
                    }),
                    map = new L.Map("map", {
                        center: new L.LatLng(-40.62030677617555, -72.71502215477118),
                        zoom: 2,
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
</section>
