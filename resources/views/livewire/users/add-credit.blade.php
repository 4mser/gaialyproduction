<section>
    <livewire:components.title-bar title="{{ $title }}" />
    <div class="px-2">
        <div class="w-full bg-white p-4 md:w-1/2 md:rounded-lg md:shadow-md lg:w-4/12">
            <form class="w-full">
                @csrf
                <div class="-mx-3 mb-6 flex flex-wrap items-stretch">
                    <div class="mb-6 w-full px-3">
                        <x-jet-label class="text-xs" for="name" value="{{ __('Name') }}" />
                        <x-jet-input id="name" type="text" class="mt-1 block w-full bg-gray-100 text-xs" value="{{ $User->name }}" />
                        <x-jet-input-error for="name" class="mt-2" />
                    </div>
                    <div class="mb-6 w-full px-3">
                        <x-jet-label class="text-xs" for="lastName" value="{{ __('Last name') }}" />
                        <x-jet-input id="last_name" type="text" class="mt-1 block w-full bg-gray-100 text-xs" value="{{ $User->last_name }}" />
                        <x-jet-input-error for="lastName" class="mt-2" />
                    </div>
                    <div class="mb-6 w-full px-3">
                        <x-jet-label class="text-xs" for="email" value="{{ __('Email') }}" />
                        <x-jet-input id="email" type="text" class="mt-1 block w-full bg-gray-100 text-xs" value="{{ $User->email }}" disabled />
                    </div>
                    <div class="mb-6 w-full px-3">
                        <x-jet-label class="text-xs" for="rut" value="{{ __('RUT/DNI/ID') }}" />
                        <x-jet-input id="rut" type="text" class="mt-1 block w-full bg-gray-100 text-xs" value="{{ $User->rut }}" disabled />
                    </div>
                    <div class="mb-6 w-full px-3">
                        <x-jet-label class="text-xs" for="rut" value="{{ __('Current balance') }}" />
                        <x-jet-input id="rut" type="text" class="mt-1 block w-full bg-gray-100 text-xs" value="{{ $User->credit_balance }}" disabled />
                    </div>
                    <div class="mb-6 w-full px-3">
                        <x-jet-label class="text-lg" for="rut" value="{{ __('Credits to add') }}" />
                        <x-jet-input wire:model="credit" id="credit" name="credit" type="number" class="mt-1 block w-full text-lg" />
                        <x-jet-input-error for="credit" class="mt-2" />
                    </div>
                </div>
                <div class="flex flex-row-reverse text-xs">
                    <button class="bg-primary focus:shadow-outline ml-4 rounded px-4 py-2 font-bold text-black shadow hover:bg-blue-300 focus:outline-none" type="button" onClick="confirm('','{{ __('Are you sure you want to add credit?') }}','addCredit');">
                        {{ __('Save') }}
                    </button>
                    <button class="focus:shadow-outline rounded bg-gray-500 px-4 py-2 font-bold text-white shadow hover:bg-gray-700 focus:outline-none" type="button" wire:click="volver">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div wire:loading wire:target="save">
        <livewire:components.loading>
    </div>
</section>
