<section>
    @if (count($items) > 0)
        <table class="pagination-table flex-no-wrap mt-2 flex w-full flex-row divide-y divide-gray-200 overflow-hidden sm:mt-0 sm:bg-white sm:shadow-lg">
            <thead class="sm:hidden">
                @foreach ($items as $layer)
                    <tr class="flex-no wrap mb-2 flex flex-col rounded-l-lg text-left text-sm font-medium uppercase text-gray-500 sm:mb-0 sm:table-row sm:rounded-none">
                        <th class="border-b border-t border-gray-200 bg-gray-50 p-2 sm:hidden">
                            {{ __('Name') }}
                        </th>
                        <th class="border-b border-gray-200 bg-gray-50 p-2 sm:hidden sm:border-t">
                            <div class="sm:hidden">{{ __('Action') }}</div>
                        </th>
                    </tr>
                @endforeach
            </thead>
            <tbody class="flex-1 divide-y divide-gray-200 bg-white sm:flex-none">
                @foreach ($items as $layer)
                    <tr class="flex-no wrap mb-2 flex flex-col text-sm text-gray-900 sm:mb-0 sm:table-row">
                        <td class="p-2">
                            <div class="truncate sm:whitespace-normal">{{ $layer['name'] }}
                            </div>
                        </td>
                        <td class="whitespace-nowrap p-2">
                            <div class="sm:hidden">
                                <a title="{{ __('Edit area') }}" href="/" class="mr-8 cursor-pointer text-blue-600 hover:underline">{{ __('Edit') }}</a>

                                <a onClick="confirm('{{ $layer['id'] }}','{{ __('Are you sure you want to remove this layer?') }}','deleteLayer')" title="{{ __('Delete layer') }}" class="cursor-pointer text-red-700 hover:underline">{{ __('Delete') }}</a>
                            </div>
                            <div class="hidden text-right sm:block">
                                @if ($layer['visible'] === true)
                                    <div title="{{ __('Center on map') }}" onClick="centerMap('{!! base64_encode($layer['geom']) !!}',{{ $layer['layer_type_id'] }})" class="inline-flex cursor-pointer items-center rounded bg-gray-700 p-1 font-bold text-white hover:bg-gray-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <button title="{{ __('Delete layer') }}" onClick="confirm('{{ $layer['id'] }}','{{ __('Are you sure you want to remove this layer?') }}','deleteLayer')" class="inline-flex items-center rounded bg-red-700 p-1 font-bold text-white hover:bg-red-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                @else
                                    <div>Pending</div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="py-6 text-center text-sm text-gray-500">
            <i><strong>{{ __('No records') }}</strong></i>
        </div>
    @endif
    <script>
        function centerMap(coords, type) {

            if (type != 3) {
                coords = atob(coords);
                coords = JSON.parse(coords);
                bounds = L.geoJSON(coords).getBounds();
            } else {
                bounds = [];
                coords = atob(coords);

                JSON.parse(coords).forEach(element => {
                    bounds.push([element[1], element[0]]);
                });
                bounds = L.latLngBounds(bounds);

            }
            map.fitBounds(bounds);

        }
    </script>
</section>
