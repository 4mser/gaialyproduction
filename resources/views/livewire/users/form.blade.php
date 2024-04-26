<section>
    <livewire:components.title-bar title="{{ $title }}" />
    <div class="px-2">
        <div class="w-full bg-white p-4 md:rounded-lg md:shadow-md">
            <form class="w-full">
                @csrf
                <div class="-mx-3 mb-6 flex flex-wrap items-stretch">
                    <div class="mb-6 w-full px-3 md:mb-5 md:w-4/12">
                        <x-jet-label class="text-xs" for="name" value="{{ __('Name') }}" />
                        <x-jet-input wire:model="name" id="name" type="text" class="mt-1 block w-full text-xs" />
                        <x-jet-input-error for="name" class="mt-2" />
                    </div>
                    <div class="mb-6 w-full px-3 md:mb-5 md:w-4/12">
                        <x-jet-label class="text-xs" for="lastName" value="{{ __('Last name') }}" />
                        <x-jet-input wire:model="lastName" id="last_name" type="text" class="mt-1 block w-full text-xs" />
                        <x-jet-input-error for="lastName" class="mt-2" />
                    </div>
                    <div class="mb-6 w-full px-3 md:mb-5 md:w-4/12">
                        <x-jet-label class="text-xs" for="email" value="{{ __('Email') }}" />
                        <x-jet-input wire:model="email" id="email" type="text" class="mt-1 block w-full text-xs" />
                        <x-jet-input-error for="email" class="mt-2" />
                    </div>
                    <div class="mb-6 w-full px-3 md:mb-5 md:w-4/12">
                        <x-jet-label class="text-xs" for="rut" value="{{ __('RUT/DNI/ID') }}" />
                        <x-jet-input wire:model="rut" id="rut" type="text" class="mt-1 block w-full text-xs" />
                        <x-jet-input-error for="rut" class="mt-2" />
                    </div>
                    <div class="mb-6 w-full px-3 md:mb-5 md:w-4/12">
                        <x-jet-label class="text-xs" for="phone" value="{{ __('Phone') }}" />
                        <x-jet-input wire:model="phone" id="phone" type="number" class="mt-1 block w-full text-xs" />
                        <x-jet-input-error for="phone" class="mt-2" />
                    </div>
                    <div class="mb-6 w-full px-3 md:mb-5 md:w-4/12">
                        <x-jet-label class="text-xs" for="title" value="{{ __('Title') }}" />
                        <x-jet-input wire:model="title" id="title" type="text" class="mt-1 block w-full text-xs" />
                        <x-jet-input-error for="text" class="mt-2" />
                    </div>
                    <div class="mb-6 w-full px-3 md:mb-5 md:w-4/12">
                        <x-jet-label class="text-xs" for="company_id" value="{{ __('Company') }}" />
                        <select wire:model="companyId" class="mt-1 block w-full rounded-md border-gray-300 text-xs shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="company_id" name="company_id">
                            @foreach ($companies as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <x-jet-input-error for="companyId" class="mt-2" />
                    </div>
                </div>
                <div>
                    <div class="md:flex md:items-center">
                        <div class="text-xs md:w-full">
                            <button class="bg-primary focus:shadow-outline float-right rounded px-4 py-2 font-bold text-black shadow hover:bg-blue-300 focus:outline-none" type="button" wire:click="save">
                                {{ __('Save') }}
                            </button>
                            <button class="focus:shadow-outline float-right mr-4 rounded bg-gray-500 px-4 py-2 font-bold text-white shadow hover:bg-gray-700 focus:outline-none" type="button" wire:click="volver">
                                {{ __('Cancel') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div wire:loading wire:target="save">
        <livewire:components.loading>
    </div>
</section>
