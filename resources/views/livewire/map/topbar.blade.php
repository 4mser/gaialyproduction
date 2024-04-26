<section class="map-topbar m-2">
    <div class="w-full flex justify-between h-10">
        <div class="relative mr-2 w-full">
            <div class="absolute flex items-center h-full px-3">
                <svg width="18" height="18" class="w-4 lg:w-auto" viewBox="0 0 18 18" fill="none"
                    xmlns="https://www.w3.org/2000/svg">
                    <path
                        d="M8.11086 15.2217C12.0381 15.2217 15.2217 12.0381 15.2217 8.11086C15.2217 4.18364 12.0381 1 8.11086 1C4.18364 1 1 4.18364 1 8.11086C1 12.0381 4.18364 15.2217 8.11086 15.2217Z"
                        stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M16.9993 16.9993L13.1328 13.1328" stroke="#455A64" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </div>
            <input wire:model="search" type="text" name="search"
                class="pl-10 h-10 text-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full"
                placeholder="{{ __('Search') }}" />
        </div>
        <select wire:model="operationTypeId"
            class="text-sm order-3 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full"
            name="operation_type_id">
            <option value="">{{ __('All operations by type') }}</option>
            @foreach ($operationTypeOptions as $key => $operationType)
            <option value="{{ $key }}">{{ $operationType }}</option>
            @endforeach
        </select>
        @if (auth()->user()->isSuperAdminProfile())
        <select wire:model="companyId"
            class="text-sm order-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full sm:ml-2 sm:mb-0 sm:order-3"
            name="company_id">
            @foreach ($companyOptions as $key => $name)
            <option value="{{ $key }}">{{ $name }}</option>
            @endforeach
        </select>
        @endif
        {{-- <a title="{{ __('Create operation') }}" href="{{ route('inspections.form') }}"
            title="{{ __('Create operation') }}"
            class="order-3 mb-2 p-3 text-black bg-primary inline-flex items-center rounded-md hover:bg-blue-300 hover:text-black-400 transition duration-200 ease-in-out sm:ml-2 sm:mb-0 sm:order-6">
            <svg class="-ml-1 mr-1 h-4" xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            {{ __('Create') }}
        </a> --}}
    </div>
</section>