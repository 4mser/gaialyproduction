<section class="flex flex-row">
    <div class="map flex h-screen w-8/12" wire:ignore id="map">
    </div>
    <div class="mx-2 flex h-screen w-4/12 flex-col overflow-y-auto px-2 py-4">
        <div class="flex flex-row justify-between">
            <h1 class="text-lg">{{ __('Detections') }}</h1>
            {{-- Times button --}}
            <button class="bg-gray-300 px-2 py-1 hover:bg-gray-500" wire:click="goBack">
                {{ __('Go back') }}
            </button>
            {{-- <button class="focus:outline-none" wire:click="goBack">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button> --}}
        </div>
        {{-- show image metadata in a table --}}
        <div>
            <table class="mb-6 mt-2 w-full table-auto bg-gray-50 text-sm shadow">
                <thead class="mb-4 flex w-full text-white">
                    <tr class="flex w-full bg-gray-800 text-white">
                        <th class="w-2/4 p-2">{{ __('Attribute') }}</th>
                        <th class="w-2/4 p-2">{{ __('Value') }}</th>
                    </tr>
                </thead>
                <tbody class="flex-start flex h-auto w-full flex-col items-center overflow-y-scroll">
                    <tr class="mb-1 flex w-full justify-items-center font-light text-gray-900">
                        <td class="w-2/4 p-1 text-center font-bold">
                            {{ __('Original name') }}
                        </td>
                        <td class="w-2/4 p-1 text-center">
                            {{ $imageData['metadata_original_name'] }}
                        </td>
                    </tr>
                    <tr class="mb-1 flex w-full justify-items-center font-light text-gray-900">
                        <td class="w-2/4 p-1 text-center font-bold">
                            {{ __('Creation date') }}
                        </td>
                        <td class="w-2/4 p-1 text-center">
                            {{ $imageData['metadata_date'] }}
                        </td>
                    </tr>
                    <tr class="mb-1 flex w-full justify-items-center font-light text-gray-900">
                        <td class="w-2/4 p-1 text-center font-bold">
                            {{ __('Latitude') }}
                        </td>
                        <td class="w-2/4 p-1 text-center">
                            {{ $imageData['metadata_lat'] }}
                        </td>
                    </tr>
                    <tr class="mb-1 flex w-full justify-items-center font-light text-gray-900">
                        <td class="w-2/4 p-1 text-center font-bold">
                            {{ __('Longitude') }}
                        </td>
                        <td class="w-2/4 p-1 text-center">
                            {{ $imageData['metadata_lng'] }}
                        </td>
                    </tr>
                    @if ($tempMax && $tempMin && $tempAvg)
                        <tr class="mb-1 flex w-full justify-items-center font-light text-gray-900">
                            <td class="w-2/4 p-1 text-center font-bold">
                                {{ __('Temp min') }}
                            </td>
                            <td class="w-2/4 p-1 text-center">
                                {{ $tempMin }} °C
                            </td>
                        </tr>
                        <tr class="mb-1 flex w-full justify-items-center font-light text-gray-900">
                            <td class="w-2/4 p-1 text-center font-bold">
                                {{ __('Temp max') }}
                            </td>
                            <td class="w-2/4 p-1 text-center">
                                {{ $tempMax }} °C
                            </td>
                        </tr>
                        <tr class="mb-1 flex w-full justify-items-center font-light text-gray-900">
                            <td class="w-2/4 p-1 text-center font-bold">
                                {{ __('Temp avg') }}
                            </td>
                            <td class="w-2/4 p-1 text-center">
                                {{ number_format($tempAvg, 1) }} °C
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <table class="mb-6 mt-2 w-full table-auto bg-gray-50 text-sm shadow">
            <thead class="mb-4 flex w-full text-white">
                <tr class="flex w-full bg-gray-800 text-white">
                    <th class="w-1/4 p-2">{{ __('Description') }}</th>
                    <th class="w-1/4 p-2">{{ __('Confidence') }}</th>
                    <th class="w-1/4 p-2">{{ __('Severity') }}</th>
                    <th class="w-1/4 cursor-pointer p-2 text-right">
                        <button class="bg-gray-300 px-3 py-1 text-black hover:bg-gray-500" wire:click="showDetails('new')">
                            {{ __('Add') }}
                        </button>
                </tr>
            </thead>
            <tbody class="flex-start flex h-60 w-full flex-col items-center overflow-y-scroll">
                @forelse ($imageData["detections"] as $detection)
                    <tr class="mb-4 flex w-full justify-items-center font-light text-gray-900">
                        <td class="w-1/4 p-2 text-center">
                            {{ $detection['label'] }}
                        </td>
                        <td class="w-1/4 p-2 text-center">
                            {{ $detection['confidence'] . '%' }}
                        </td>
                        <td class="w-1/4 p-2 pl-6 text-center">
                            {{ $detection['severity'] == 6 ? '?' : $detection['severity'] }}
                        </td>
                        <td class="flex w-1/4 flex-row-reverse p-2 text-right">
                            <span class="cursor-pointer" wire:click="removeDetails({{ $detection['id'] }})">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-2 h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </span>

                            <span class="cursor-pointer" wire:click="showDetails({{ $detection['id'] }})">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-2 h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                </svg>
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr class="mb-4 flex w-full justify-items-center font-light text-gray-900">
                        <td class="w-full p-2 text-center">
                            {{ __('No detections') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if (!is_null($selectedDetectionId))
            <div class="border text-sm">
                <div class="flex flex-row justify-between p-2">
                    <h1 class="text-md">Details</h1>
                    <button class="bg-gray-300 px-2 py-1 hover:bg-gray-500" onclick="window.location.reload();">
                        {{ __('Close list') }}
                    </button>
                    {{-- Times button --}}
                    {{-- <button class="focus:outline-none" onclick="window.location.reload();">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button> --}}
                </div>
                <div class="bg-white p-6">
                    <div class="mb-3 flex items-center">
                        <div class="mr-2 flex w-3/12 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="mr-1 h-4 w-4">
                                <path fill-rule="evenodd" d="M12 6.75a5.25 5.25 0 016.775-5.025.75.75 0 01.313 1.248l-3.32 3.319c.063.475.276.934.641 1.299.365.365.824.578 1.3.64l3.318-3.319a.75.75 0 011.248.313 5.25 5.25 0 01-5.472 6.756c-1.018-.086-1.87.1-2.309.634L7.344 21.3A3.298 3.298 0 112.7 16.657l8.684-7.151c.533-.44.72-1.291.634-2.309A5.342 5.342 0 0112 6.75zM4.117 19.125a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75h-.008a.75.75 0 01-.75-.75v-.008z" clip-rule="evenodd" />
                            </svg>
                            {{ __('Label') }}
                        </div>
                        <div class="w-9/12">
                            <x-jet-input wire:model="label" type="text" class="mt-1 block w-full" />
                        </div>
                    </div>
                    <div class="mb-3 flex items-center">
                        <div class="mr-2 flex w-3/12 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="mr-1 h-4 w-4">
                                <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                            </svg>
                            {{ __('Severity') }}
                        </div>
                        <div class="w-9/12">
                            <button type="button" wire:click="setSeverity(1)" class="{{ $severity == 1 ? 'bg-red-500' : '' }} mb-2 mr-2 h-8 w-8 border-2 border-b-4 border-red-500 text-sm hover:bg-red-500">1</button>
                            <button type="button" wire:click="setSeverity(2)" class="{{ $severity == 2 ? 'bg-yellow-500' : '' }} mb-2 mr-2 h-8 w-8 border-2 border-b-4 border-yellow-500 text-sm hover:bg-yellow-500">2</button>
                            <button type="button" wire:click="setSeverity(3)" class="{{ $severity == 3 ? 'bg-yellow-200' : '' }} mb-2 mr-2 h-8 w-8 border-2 border-b-4 border-yellow-200 text-sm hover:bg-yellow-200">3</button>
                            <button type="button" wire:click="setSeverity(4)" class="{{ $severity == 4 ? 'bg-green-400' : '' }} mb-2 mr-2 h-8 w-8 border-2 border-b-4 border-green-400 text-sm hover:bg-green-400">4</button>
                            <button type="button" wire:click="setSeverity(5)" class="{{ $severity == 5 ? 'bg-green-700' : '' }} mb-2 mr-2 h-8 w-8 border-2 border-b-4 border-green-700 text-sm hover:bg-green-700">5</button>
                            <button type="button" wire:click="setSeverity(6)" class="{{ $severity == 6 ? 'bg-purple-500' : '' }} mb-2 mr-2 h-8 w-8 border-2 border-b-4 border-purple-500 text-sm hover:bg-purple-500">?</button>
                        </div>
                    </div>
                    <div class="mb-3 flex items-center">
                        <div class="mr-2 flex w-3/12">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="mr-1 h-4 w-4">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                            {{ __('Solved') }}
                        </div>
                        <div class="w-9/12">
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
                            <div class="relative mr-2 inline-block w-10 select-none align-middle transition duration-200 ease-in">
                                <input type="checkbox" wire:click="setSolved()" name="toggle" id="toggle" {{ $solved ? 'checked' : '' }} class="toggle-checkbox absolute block h-6 w-6 cursor-pointer appearance-none rounded-full border-4 bg-white" />
                                <label for="toggle" class="toggle-label block h-6 cursor-pointer overflow-hidden rounded-full bg-gray-300"></label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 flex items-center">
                        <div class="mr-2 flex w-3/12 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="mr-1 h-4 w-4">
                                <path fill-rule="evenodd" d="M12 6.75a5.25 5.25 0 016.775-5.025.75.75 0 01.313 1.248l-3.32 3.319c.063.475.276.934.641 1.299.365.365.824.578 1.3.64l3.318-3.319a.75.75 0 011.248.313 5.25 5.25 0 01-5.472 6.756c-1.018-.086-1.87.1-2.309.634L7.344 21.3A3.298 3.298 0 112.7 16.657l8.684-7.151c.533-.44.72-1.291.634-2.309A5.342 5.342 0 0112 6.75zM4.117 19.125a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75h-.008a.75.75 0 01-.75-.75v-.008z" clip-rule="evenodd" />
                            </svg>
                            {{ __('Remedy action') }}
                        </div>
                        <div class="w-9/12">
                            <textarea rows="3" wire:model="remedy" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                        </div>
                    </div>
                    <div class="mb-3 flex items-center">
                        <div class="mr-2 flex w-3/12 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="mr-2 h-5 w-5">
                                <path d="M10.75 10.818v2.614A3.13 3.13 0 0011.888 13c.482-.315.612-.648.612-.875 0-.227-.13-.56-.612-.875a3.13 3.13 0 00-1.138-.432zM8.33 8.62c.053.055.115.11.184.164.208.16.46.284.736.363V6.603a2.45 2.45 0 00-.35.13c-.14.065-.27.143-.386.233-.377.292-.514.627-.514.909 0 .184.058.39.202.592.037.051.08.102.128.152z" />
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-6a.75.75 0 01.75.75v.316a3.78 3.78 0 011.653.713c.426.33.744.74.925 1.2a.75.75 0 01-1.395.55 1.35 1.35 0 00-.447-.563 2.187 2.187 0 00-.736-.363V9.3c.698.093 1.383.32 1.959.696.787.514 1.29 1.27 1.29 2.13 0 .86-.504 1.616-1.29 2.13-.576.377-1.261.603-1.96.696v.299a.75.75 0 11-1.5 0v-.3c-.697-.092-1.382-.318-1.958-.695-.482-.315-.857-.717-1.078-1.188a.75.75 0 111.359-.636c.08.173.245.376.54.569.313.205.706.353 1.138.432v-2.748a3.782 3.782 0 01-1.653-.713C6.9 9.433 6.5 8.681 6.5 7.875c0-.805.4-1.558 1.097-2.096a3.78 3.78 0 011.653-.713V4.75A.75.75 0 0110 4z" clip-rule="evenodd" />
                            </svg>
                            {{ __('Repair cost') }}
                        </div>
                        <div class="w-9/12">
                            <div class="flex">
                                <input class="mt-1 block w-8/12 rounded-md rounded-r-none border-gray-300 text-sm shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" wire:model="cost">
                                <select wire:model="currency" class="mt-1 block w-4/12 rounded-md rounded-l-none border-l-0 border-gray-300 text-sm shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @foreach ($currencies as $cur)
                                        <option value="{{ $cur->code }}" @if ($cur->code == $currency) selected @endif>{{ $cur->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 flex items-center">
                        <div class="mr-2 flex w-3/12 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="mr-2 h-5 w-5">
                                <path fill-rule="evenodd" d="M5.5 3A2.5 2.5 0 003 5.5v2.879a2.5 2.5 0 00.732 1.767l6.5 6.5a2.5 2.5 0 003.536 0l2.878-2.878a2.5 2.5 0 000-3.536l-6.5-6.5A2.5 2.5 0 008.38 3H5.5zM6 7a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            {{ __('Category') }}
                        </div>
                        <div class="w-9/12">
                            <select wire:model="category" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="setPrice(this)">
                                <option value="">...</option>
                                @foreach ($findingTypeOptions as $findingType)
                                    <option value="{{ $findingType["id"] }}" price="{{ $findingType["price"] }}" currency="{{ $findingType["currency"] }}">{{ $findingType["name"] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 flex items-center">
                        <div class="mr-2 flex w-3/12 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                            {{ __('Confidence') }}
                        </div>
                        <div class="w-9/12">
                            <x-jet-input wire:model="confidence" type="number" min="0" max="100" class="mt-1 block w-full" />
                        </div>
                    </div>
                    @if ($Image->layer_type_id == App\Models\LayerType::THERMO && $temp_max && $temp_min && $temp_avg)
                        <div class="mb-3 flex items-center">
                            <div class="mr-2 flex w-3/12 items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="mr-1 h-5 w-5">
                                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm.53 5.47a.75.75 0 00-1.06 0l-3 3a.75.75 0 101.06 1.06l1.72-1.72v5.69a.75.75 0 001.5 0v-5.69l1.72 1.72a.75.75 0 101.06-1.06l-3-3z" clip-rule="evenodd" />
                                </svg>

                                {{ __('Temp max') }}
                            </div>
                            <div class="w-9/12">
                                <p>{{ $temp_max }} °C</p>
                            </div>
                        </div>
                        <div class="mb-3 flex items-center">
                            <div class="mr-2 flex w-3/12 items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="mr-1 h-5 w-5">
                                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-.53 14.03a.75.75 0 001.06 0l3-3a.75.75 0 10-1.06-1.06l-1.72 1.72V8.25a.75.75 0 00-1.5 0v5.69l-1.72-1.72a.75.75 0 00-1.06 1.06l3 3z" clip-rule="evenodd" />
                                </svg>

                                {{ __('Temp min') }}
                            </div>
                            <div class="w-9/12">
                                <p>{{ $temp_min }} °C</p>
                            </div>
                        </div>
                        <div class="mb-3 flex items-center">
                            <div class="mr-2 flex w-3/12 items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="mr-1 h-5 w-5">
                                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm4.28 10.28a.75.75 0 000-1.06l-3-3a.75.75 0 10-1.06 1.06l1.72 1.72H8.25a.75.75 0 000 1.5h5.69l-1.72 1.72a.75.75 0 101.06 1.06l3-3z" clip-rule="evenodd" />
                                </svg>



                                {{ __('Temp avg') }}
                            </div>
                            <div class="w-9/12">
                                <p>{{ $temp_avg }} °C</p>
                            </div>
                        </div>
                    @endif
                    <div class="flex justify-end">
                        <button onclick="window.location.reload();" type="button" class="inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto sm:text-sm">
                            {{ __('Cancel') }}
                        </button>
                        <button type="button" onclick="saveLayer()" class="bg-primary ml-2 inline-flex w-full justify-center rounded-md border border-transparent px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto sm:text-sm">
                            {{ __('Save') }}
                        </button>
                    </div>

                </div>
            </div>
        @endif
    </div>
</section>

@push('styles')
    <style>
        .leaflet-container {
            background: #000;
            outline: 0;
        }
    </style>
@endpush
@push('scripts')
    <script>
        document.addEventListener("livewire:load", function() {
            var yx = L.latLng;
            var xy = function(x, y) {
                if (L.Util.isArray(x)) {
                    // When doing xy([x, y]);
                    return yx(x[1], x[0]);
                }
                return yx(y, x); // When doing xy(x, y);
            };

            var imageUrl = "{{ url('storage/' . $Image->file_name) }}";
            var x = {{ $imageData['width'] }};
            var y = {{ $imageData['height'] }};

            var bounds = [
                [0, 0],
                [y, x],
            ];

            var bounds = [xy(0, 0), xy(x, y)];

            var map = L.map("map", {
                crs: L.CRS.Simple,
                // zoom: 0,
                minZoom: -2,
            });
            if ({!! $Image['layer_type_id'] !!} === {!! App\Models\LayerType::THERMO !!}) {
                jsonData = {!! $thermoData !!};
                L.control.mousePosition({
                    position: "topright",
                    formatter: (e, f) => (
                        f.toFixed(0) >= 0 && f.toFixed(0) <= 512 && e.toFixed(0) >= 0 && e.toFixed(0) <= 640 ? `${ jsonData[512-f.toFixed(0)][e.toFixed(0)] } °C` : ''),

                    wrapLng: false
                }).addTo(map);
            }

            var image = L.imageOverlay(imageUrl, bounds).addTo(map);
            map.fitBounds([xy(0, 0), xy(x, y)]);

            drawnItems = new L.FeatureGroup();
            map.addLayer(drawnItems);

            drawnLayers = [];
            @foreach ($imageData['detections'] as $key => $detection)
                if ({!! $Image['layer_type_id'] !!} === {!! App\Models\LayerType::THERMO !!}) {
                    hoverText = `<p>{{ $detection['label'] . ' ' . $detection['confidence'] }}%</p>
                        <p>{{ __('Temp min') }}: {{ $detection['temp_min'] }} °C</p>
                        <p>{{ __('Temp avg') }}: {{ $detection['temp_avg'] }} °C</p>
                        <p>{{ __('Temp max') }}: {{ $detection['temp_max'] }} °C</p>`;
                } else {
                    hoverText = `<p>{{ $detection['label'] . ' ' . $detection['confidence'] }}%</p>`;
                }
                drawnLayers[{{ $key }}] =
                    L.geoJSON({!! $detection['geom'] !!}, {
                        style: {!! $detection['simbology'] !!},
                    }).bindTooltip(
                        `<p>${hoverText}</p>`, {
                            opacity: 0.7,
                            sticky: true {{ $showLabels ? ', permanent: true' : '' }}
                        }
                    );
                drawnItems.addLayer(drawnLayers[{{ $key }}]);
                drawnLayers[{{ $key }}].addTo(map);
            @endforeach
            map.on('pm:create', (e) => {
                e.layer.options.pmIgnore = false;
                L.PM.reInitLayer(e.layer);
            });


            Livewire.on('editLayer', () => {
                drawnItems.eachLayer(function(layer) {
                    layer.pm.disable();
                });
                drawnLayers[@this.selectedDetectionId].pm.enable({
                    allowSelfIntersection: false,
                });
            });

            Livewire.on('drawLayer', () => {
                drawnItems.eachLayer(function(layer) {
                    layer.pm.disable();
                });
                map.pm.enableDraw('Polygon', {
                    allowSelfIntersection: true,

                });
                // on draw end save the layer data
                map.on('pm:create', (e) => {
                    e.layer.options.pmIgnore = false;
                    L.PM.reInitLayer(e.layer);
                    drawnLayers[@this.selectedDetectionId] = e.layer;
                });

            });

        });

        const saveLayer = () => {
            if (!drawnLayers[@this.selectedDetectionId]) {
                toast("error", "Please draw a polygon")
                return;
            }
            @this.geom = drawnLayers[@this.selectedDetectionId].toGeoJSON();
            @this.saveForm();
        }

        const setPrice = (e) => {
            @this.cost = e.options[e.selectedIndex].getAttribute('price');
            @this.currency = e.options[e.selectedIndex].getAttribute('currency');
        }
    </script>
@endpush
