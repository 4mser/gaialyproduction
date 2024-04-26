<div>
    <x-modal openModal="{{ $openModal }}">
        <x-slot name="title" wire:click="closeModal">Text...</x-slot>
        Form...
        {{-- <img class="image-form"
            src="https://media.istockphoto.com/vectors/image-preview-icon-picture-placeholder-for-website-or-uiux-design-vector-id1222357475?b=1&k=20&m=1222357475&s=170667a&w=0&h=S4rQXn5gFTcmcZJSBOxKr1ufmasJRLUX5dcBhBB6V-A="
            alt="image"> --}}

        {{-- <form class="text-xs" wire:submit.prevent="save">
            <div class="flex flex-col items-center mb-2">
                <div class="flex flex-nowrap items-center mb-2">
                    <svg xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <label for="title" class="text-sm">{{ __('Severity:') }}</label>
    <x-modal-component showModal="{{ $openForm }}" title="{{ $title }}">
        <div class="grid place-items-center col-span-2">
            <img class="w-[36rem] h-[36rem]"
                src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/Red_X.svg/2048px-Red_X.svg.png"
                alt="image" />
        </div>
        <form class="text-xs" wire:submit.prevent="save">
            <div class="flex flex-col mb-2">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                            <table class="min-w-full text-center">
                                <thead>
                                    <tr>
                                        <th scope="col" class="flex flex-col text-sm font-medium text-gray-900 py-2">
                                            <div class="flex flex-nowrap">
                                                <svg xmlns="https://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                                </svg>
                                                <label for="title"
                                                    class="text-sm ml-1">{{ __('Findings: ') }}</label>
                                            </div>
                                        </th>
                                        <th scope="col" class="text-sm font-bold text-gray-700 px-6 py-2">
                                            {{ __('Description') }}
                                        </th>
                                        <th scope="col" class="text-sm font-bold text-gray-700 px-6 py-2">
                                            {{ __('Date') }}
                                        </th>
                                        <th scope="col" class="text-sm font-bold text-gray-700 px-2 py-2">
                                            {{ __('+Add') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-sm text-gray-900 font-medium px-6 py-2 whitespace-nowrap">
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-2 whitespace-nowrap">
                                            Broken Pipe
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-2 whitespace-nowrap">
                                            01/01/2022
                                        </td>
                                        <td
                                            class="flex flex-col text-sm text-gray-900 font-light px-6 py-2 whitespace-nowrap">
                                            <div class="flex flex-nowrap ml-4">
                                                <svg xmlns="https://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                                </svg>
                                                <svg xmlns="https://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="ml-2 w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-sm text-gray-900 font-medium px-6 py-2 whitespace-nowrap">
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-2 whitespace-nowrap">
                                            Cut Strand
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-2 whitespace-nowrap">
                                            01/01/2022
                                        </td>
                                        <td
                                            class="flex flex-col text-sm text-gray-900 font-light px-6 py-2 whitespace-nowrap">
                                            <div class="flex flex-nowrap ml-4">
                                                <svg xmlns="https://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                                </svg>
                                                <svg xmlns="https://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="ml-2 w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-sm text-gray-900 font-medium px-6 py-2 whitespace-nowrap">
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-2 whitespace-nowrap">
                                            Broken Insulator
                                        </td>
                                        <td class="text-sm text-gray-900 font-light px-6 py-2 whitespace-nowrap">
                                            01/01/2022
                                        </td>
                                        <td
                                            class="flex flex-col text-sm text-gray-900 font-light px-6 py-2 whitespace-nowrap">
                                            <div class="flex flex-nowrap ml-4">
                                                <svg xmlns="https://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                                </svg>
                                                <svg xmlns="https://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="ml-2 w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-300 h-10">
                    <div class="grid grid-cols-2">
                        <div class="ml-4 mt-2 text-left text-sm font-bold text-gray-700">
                            {{ __('Details') }}
                        </div>
                        <div class="flex justify-end mt-2 mr-2">
                            <svg xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row items-center mt-6">
                    <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-5">
                        <path fill-rule="evenodd"
                            d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"
                            clip-rule="evenodd" />
                    </svg>
                    <label for="title" class="text-sm ml-2 mr-4 text-gray-900">{{ __('Severity:') }}</label>
                    <button type="button"
                        class="w-10 h-10 mr-2 border-2 border-b-4 border-green-400 hover:bg-green-400 text-sm">1</button>
                    <button type="button"
                        class="w-10 h-10 mr-2 border-2 border-b-4 border-green-500 hover:bg-green-500 text-sm">2</button>
                    <button type="button"
                        class="w-10 h-10 mr-2 border-2 border-b-4 border-yellow-400 hover:bg-yellow-400 text-sm">3</button>
                    <button type="button"
                        class="w-10 h-10 mr-2 border-2 border-b-4 border-yellow-500 hover:bg-yellow-500 text-sm">4</button>
                    <button type="button"
                        class="w-10 h-10 mr-2 border-2 border-b-4 border-red-500 hover:bg-red-500 text-sm">5</button>
                    <button type="button"
                        class="w-10 h-10 mr-2 border-2 border-b-4 border-purple-500 hover:bg-purple-500 text-sm">?</button>
                </div>
            </div>
            <div class="flex flex-col mt-6">
                <div class="flex flex-nowrap">
                    <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-5">
                        <path fill-rule="evenodd"
                            d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                            clip-rule="evenodd" />
                    </svg>
                    <label for="toggle" class="text-sm ml-2 mr-4 text-gray-900 w-30">{{ __('Solved:') }}</label>
                    <div
                        class="relative inline-block w-14 mr-2 ml-2 align-middle select-none transition duration-200 ease-in">
                        <input type="checkbox" name="toggle" id="toggle"
                            class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                        <label for="toggle"
                            class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                    </div>
                </div>
            </div>
            <div class="flex flex-col mt-6">
                <div class="flex flex-nowrap items-center">
                    <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-5">
                        <path fill-rule="evenodd"
                            d="M12 6.75a5.25 5.25 0 016.775-5.025.75.75 0 01.313 1.248l-3.32 3.319c.063.475.276.934.641 1.299.365.365.824.578 1.3.64l3.318-3.319a.75.75 0 011.248.313 5.25 5.25 0 01-5.472 6.756c-1.018-.086-1.87.1-2.309.634L7.344 21.3A3.298 3.298 0 112.7 16.657l8.684-7.151c.533-.44.72-1.291.634-2.309A5.342 5.342 0 0112 6.75zM4.117 19.125a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75h-.008a.75.75 0 01-.75-.75v-.008z"
                            clip-rule="evenodd" />
                    </svg>
                    <label for="title" class="text-sm text-gray-900">{{ __('Remedy Action: ') }}</label>
                    <textarea placeholder="Write a remedy action" class="text-sm rounded-md border-6 text-center mt-2 ml-2 w-full"></textarea>
                </div>
            </div>
            <div class="flex flex-col mt-6">
                <div class="flex flex-nowrap">
                    <div class="text-xl"> $ </div>
                    <label for="title" class="text-sm ml-4 mt-1 mr-5">{{ __('Repair Cost:') }}</label>
                    <input
                        class="text-right ml-2 text-sm text-gray-700 rounded border-1 h-9 w-full bg-white focus:outline-none appearance-none"
                        placeholder="$ 0.00" type="text">
                    <select class="text-sm rounded text-gray-700 border-1 h-9 w-25 bg-white">
                        <option label="USD" value="string:USD" selected="selected">USD</option>
                        <option label="EUR" value="string:EUR">EUR</option>
                        <option label="GBP" value="string:GBP">GBP</option>
                        <option label="DKK" value="string:DKK">DKK</option>
                        <option label="SEK" value="string:SEK">SEK</option>
                        <option label="NOK" value="string:NOK">NOK</option>
                    </select>
                    </input>
                </div>
                <div class="flex flex-nowrap items-center mt-6">
                    <svg xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                    </svg>
                    <label for="title" class="text-sm text-gray-900 ml-2 mr-2">{{ __('Category: ') }}</label>
                    <select
                        class="text-sm ml-2 text-gray-700 rounded border-1 h-10 w-full pl-5 pr-10 bg-white focus:outline-none appearance-none">
                        <option>Insert Category</option>
                        <option>Insert Category</option>
                        <option>Insert Category</option>
                        <option>Insert Category</option>
                        <option>Insert Category</option>
                        <option>Insert Category</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end mt-6 mb-4">
                <button wire:click="hideModal()" type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('Cancel') }}
                </button>
                <button type="submit"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('Save') }}
                </button>
            </div>
        </form> --}}
    </x-modal>
</div>

<style>
    /* CHECKBOX TOGGLE SWITCH */
    .toggle-checkbox:checked {
        @apply: right-0 border-green-400;
        right: 0;
        border-color: #27b1bb;
    }

    .toggle-checkbox:checked+.toggle-label {
        @apply: bg-green-400;
        background-color: #27b1bb;
    }
</style>
