<section>
    <livewire:components.title-bar title="{{ __('Finding Types') }}" />

    <div class="inline-block w-full min-w-full align-middle md:p-2">
        <div class="overflow-hidden md:rounded-md md:shadow-md">
            <div class="flex flex-col-reverse bg-white p-2 sm:flex-row">
                <x-jet-input wire:model="search" type="text" name="search" placeholder="{{ __('Search') }}..." class="order-1 block w-full" autocomplete="off" />

                <a href="{{ route('finding-types.form') }}" title="{{ __('Create finding type') }}" class="order-2 mb-2 inline-flex items-center rounded-md bg-blue-200 p-3 text-black transition duration-200 ease-in-out hover:bg-blue-300 sm:mb-0 sm:ml-2">
                    <svg class="-ml-1 mr-1 h-4" xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>

                    {{ __('Create') }}
                </a>
            </div>
            @if ($items->count())

                <div class="overflow-hidden" style="width:100%;">
                    <table class="pagination-table flex-no-wrap flex w-full flex-row divide-y divide-gray-200 overflow-hidden sm:bg-white sm:shadow-lg">
                        <thead class="bg-white">
                            @foreach ($items as $item)
                                <tr class="flex-no wrap mb-2 flex flex-col rounded-l-lg text-left text-sm font-medium uppercase text-gray-500 sm:mb-0 sm:table-row sm:rounded-none">
                                    <th class="border-b border-t border-gray-200 bg-gray-50 p-2">
                                        <span class="block">{{ __('Name') }}</span>
                                    </th>
                                    <th class="border-b border-gray-200 bg-gray-50 p-2 sm:border-t">
                                        {{ __('Parent') }} &nbsp;
                                    </th>
                                    <th class="border-b border-gray-200 bg-gray-50 p-2 sm:border-t">
                                        {{ __('Price') }} &nbsp;
                                    </th>
                                    <th class="border-b border-gray-200 bg-gray-50 p-2 sm:border-t">
                                        <div class="sm:hidden">{{ __('Action') }}</div>
                                    </th>
                                </tr>
                            @endforeach
                        </thead>
                        <tbody class="flex-1 divide-y divide-gray-200 bg-white sm:flex-none">
                            @foreach ($items as $item)
                                <tr class="flex-no wrap mb-2 flex flex-col text-sm text-gray-900 sm:mb-0 sm:table-row">
                                    <td class="p-2">{{ $item->name }}</td>
                                    <td class="border-t border-gray-200 p-2">
                                        <div class="truncate sm:whitespace-normal">
                                            @if ($item->parentFindingType)
                                                {{ $item->parentFindingType->name }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-2">
                                        @if ($item->price)
                                            {{ $item->price }} {{ $item->currency }}
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap border-b border-t border-gray-200 p-2">
                                        <div class="block text-right">
                                            <a title="{{ __('Edit finding type') }}" href="{{ route('finding-types.form', ['id' => $item->id]) }}" class="inline-flex items-center rounded bg-gray-700 p-1 font-bold text-white hover:bg-gray-900">
                                                <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                            <button title="{{ __('Delete finding type') }}" onClick="confirm('{{ $item->id }}','{{ __('Are you sure you want to remove this finding type?') }}','deleteFindingType')" class="inline-flex items-center rounded bg-red-700 p-1 font-bold text-white hover:bg-red-900">
                                                <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-200 bg-white px-1 py-2 sm:px-3">
                    {{ $items->links() }}
                </div>
            @else
                <div class="border-t border-gray-200 bg-white px-1 py-2 text-sm text-gray-500 sm:px-3">
                    {{ __('No search results on page :page.', ['page' => $this->page]) }}
                </div>
            @endif
        </div>
    </div>
    <div wire:loading>
        <livewire:components.loading>
    </div>
</section>
