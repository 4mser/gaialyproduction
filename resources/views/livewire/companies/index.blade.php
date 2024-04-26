<section class="inline-block w-full min-w-full p-2 align-middle">
    <div class="overflow-hidden">
        <div class="flex justify-between">
            <div class="inline-flex h-12 w-3/5 rounded border bg-transparent">
                <div class="relative mb-6 flex h-full w-full flex-wrap items-stretch">
                    <div class="flex">
                        <span class="whitespace-no-wrap text-grey-dark flex items-center rounded rounded-r-none border border-r-0 border-none bg-transparent py-2 text-sm leading-normal lg:px-3">
                            <svg width="18" height="18" class="w-4 lg:w-auto" viewBox="0 0 18 18" fill="none" xmlns="https://www.w3.org/2000/svg">
                                <path d="M8.11086 15.2217C12.0381 15.2217 15.2217 12.0381 15.2217 8.11086C15.2217 4.18364 12.0381 1 8.11086 1C4.18364 1 1 4.18364 1 8.11086C1 12.0381 4.18364 15.2217 8.11086 15.2217Z" stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M16.9993 16.9993L13.1328 13.1328" stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </div>
                    <input wire:model="search" type="text" name="search" class="text-xxs lg:px- relative w-1/5 flex-1 flex-auto flex-shrink flex-grow rounded rounded-l-none border border-l-0 border-none px-3 font-thin leading-normal tracking-wide text-gray-500 focus:outline-none lg:text-xs" placeholder="{{ __('Search') }}" />
                </div>
            </div>
            <a href="{{ route('companies.form') }}" title="{{ __('Create company') }}" class="bg-primary focus:shadow-outline order-4 float-right ml-2 inline-flex items-center rounded px-4 py-2 text-xs font-bold text-black shadow hover:bg-blue-300 focus:outline-none">
                <svg class="-ml-1 mr-1 h-4" xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ __('Create') }}
            </a>

        </div>
        @if ($companies->count())
            <div class="mt-5 inline-block min-w-full overflow-hidden align-middle">
                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr class="text-primary border-b border-gray-100">
                            <th class="px-3 py-4">
                                {{ __('Company') }}
                            </th>
                            <th class="px-3 py-4">
                                {{ __('Parent company') }}
                            </th>
                            <th class="px-3 py-4">
                                {{ __('Created by') }}
                            </th>
                            <th class="px-3 py-4">
                                {{ __('Created At') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($companies as $company)
                            <tr class="border-b border-gray-100">
                                <td class="px-3 py-2">{{ $company->name }}</td>
                                <td class="px-3 py-2">
                                    @if ($company->parentCompany)
                                        {{ $company->id == $company->parentCompany->id ? '-' : $company->parentCompany->name }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    @if ($company->parentUser)
                                        {{ $company->parentUser->name }} {{ $company->parentUser->last_name }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    {{ $company->created_at->format('m/d/Y') }}
                                </td>
                                <td class="whitespace-no-wrap px-6 py-4 text-right text-xs">
                                    <a href="{{ route('companies.form', ['id' => $company->id]) }}" class="rounded border border-blue-500 px-2 py-1 text-blue-500 transition duration-300 hover:bg-blue-700 hover:text-white focus:outline-none">Edit</a>
                                    <a href="#" onClick="confirm('{{ $company->id }}','{{ __('Are you sure you want to remove this company?') }}','deleteCompany')" class="rounded border border-red-500 px-2 py-1 text-red-500 transition duration-300 hover:bg-red-700 hover:text-white focus:outline-none">{{ __('Delete') }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="bg-white px-1 py-2 sm:px-3">
                    {{ $companies->links() }}
                </div>
            </div>
        @else
            <div class="relative mx-auto mt-6 rounded py-3 text-center text-gray-700" role="alert">
                <p><strong class="font-bold">{{ __('No search results') }}</strong>
                </p>
                <span class="block sm:inline">{{ __('Nothing to show here') }}</span>
            </div>
        @endif
    </div>
    <div wire:loading>
        <livewire:components.loading>
    </div>
</section>
