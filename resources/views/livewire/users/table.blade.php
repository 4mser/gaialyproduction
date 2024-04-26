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
            <select wire:model="companyId" class="order-1 block w-full rounded-md border-gray-300 text-xs shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:order-3 sm:mb-0 sm:ml-2" name="company_id">
                @foreach ($companies as $key => $name)
                    <option value="{{ $key }}">{{ $name }}</option>
                @endforeach
            </select>
            <select wire:model="profileId" class="order-1 block w-full rounded-md border-gray-300 text-xs shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:order-3 sm:mb-0 sm:ml-2" name="company_id">
                @foreach ($profiles as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
            <a href="{{ route('users.form') }}" title="{{ __('Create user') }}" class="bg-primary focus:shadow-outline order-4 float-right ml-2 inline-flex items-center rounded px-4 py-2 text-xs font-bold text-black shadow hover:bg-blue-300 focus:outline-none">
                <svg class="-ml-1 mr-1 h-4" xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ __('Create') }}
            </a>

        </div>
        @if ($users->count())
            <div class="mt-5 inline-block min-w-full overflow-hidden align-middle">
                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr class="text-primary border-b border-gray-100">
                            <th class="px-3 py-4">{{ __('RUT/DNI/ID') }}</th>
                            <th class="px-3 py-4">{{ __('Name') }}</th>
                            <th class="px-3 py-4">{{ __('Email') }}</th>
                            <th class="px-3 py-4">{{ __('Company') }}</th>
                            @if (auth()->user()->isSuperAdminProfile() ||
                                    auth()->user()->isOwnerProfile())
                                <th class="px-3 py-4">{{ __('Role') }}</th>
                            @endif
                            <th class="px-3 py-4">{{ __('Last Visit') }}</th>
                            <th class="px-3 py-4">{{ __('Status') }}</th>
                            <th class="px-3 py-4 text-right">{{ __('Credit') }}</th>
                            <th class="px-8 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($users as $user)
                            <tr class="border-b border-gray-100">
                                <td class="px-3 py-2">{{ $user->rut }}</td>
                                <td class="px-3 py-2">{{ $user->name }} {{ $user->last_name }}</td>
                                <td class="px-3 py-2">{{ $user->email }}</td>
                                @if (auth()->user()->isSuperAdminProfile() ||
                                        auth()->user()->isOwnerProfile())
                                    <td class="px-3 py-2">
                                        {{ isset($user->company->name) ? $user->company->name : '' }}
                                    </td>
                                @endif
                                <td class="px-3 py-2">
                                    {{ isset($user->profile->name) ? $user->profile->name : '' }}
                                </td>
                                <td class="px-3 py-2">
                                    {{ $user->created_at->format('m/d/Y') }}
                                </td>
                                <td class="px-3 py-2">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </td>
                                <td class="px-3 py-2 text-right">
                                    {{ $user->credit_balance }}
                                </td>
                                <td class="px-3 py-4 text-right text-xs">
                                    @if (auth()->user()->profile_id == \App\Models\Profile::SUPER_ADMIN && $user->profile_id == \App\Models\Profile::OWNER)
                                        <a href="{{ route('users.form.add-credit', ['id' => $user->id]) }}" title="{{ __('Add credit') }}" 7 class="mb-1 ml-1 inline-block cursor-pointer rounded border border-green-500 px-2 py-1 text-center text-green-500 transition duration-300 hover:bg-green-700 hover:text-white focus:outline-none">
                                            {{ __('Add credit') }}
                                        </a>
                                    @endif
                                    <a href="{{ route('users.form', ['id' => $user->id]) }}" title="{{ __('Edit user') }}" 7 class="mb-1 ml-1 inline-block cursor-pointer rounded border border-blue-500 px-2 py-1 text-center text-center text-blue-500 transition duration-300 hover:bg-blue-700 hover:text-white focus:outline-none">
                                        {{ __('Edit') }}
                                    </a>
                                    @if (!$user->is_active)
                                        <a title="{{ __('Activate user') }}" onClick="confirm('{{ $user->id }}','{{ __('Are you sure you want to activate this user?') }}','toggleIsActiveUser')" class="mb-1 ml-1 inline-block rounded border border-green-500 px-2 py-1 text-center text-green-500 transition duration-300 hover:cursor-pointer hover:bg-green-700 hover:text-white focus:outline-none">{{ __('Activate') }}</a>
                                    @else
                                        <a title="{{ __('Deactivate user') }}" onClick="confirm('{{ $user->id }}','{{ __('Are you sure you want to deactivate this user?') }}','toggleIsActiveUser')" class="mb-1 ml-1 inline-block rounded border border-red-500 px-2 py-1 text-center text-red-500 transition duration-300 hover:cursor-pointer hover:bg-red-700 hover:text-white focus:outline-none">{{ __('Deactivate') }}</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="bg-white px-1 py-2 sm:px-3">
                    {{ $users->links() }}
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
