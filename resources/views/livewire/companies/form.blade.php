<section>
    <livewire:components.title-bar title="{{ __('Company') }}" />

    <div class="md:grid md:grid-cols-10 md:p-2">
        <div class="md:col-span-4 md:col-start-4">
            <div class="w-full overflow-hidden bg-white p-4 md:max-w-md md:rounded-lg md:shadow-md">
                <form wire:submit.prevent="save">
                    <div class="mb-6">
                        <x-jet-label class="text-xs" for="name" value="{{ __('Name') }}" />
                        <x-jet-input wire:model="name" type="text" class="mt-1 block w-full text-xs" />
                        <x-jet-input-error for="name" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-jet-label class="text-xs" for="parent_company_id" value="{{ __('Parent company') }}" />
                        <select wire:model="parent_company_id" class="mt-1 block w-full rounded-md border-gray-300 text-xs shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="parent_company_id" name="parent_company_id">
                            @foreach ($companies as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <x-jet-input-error for="parent_company_id" class="mt-2" />
                    </div>

                    <div class="mt-4 flex items-center justify-end text-xs">
                        <button wire:click="back" class="focus:shadow-outline mx-2 inline-flex items-center rounded bg-gray-500 px-4 py-2 font-bold text-white hover:bg-gray-700 focus:outline-none" type="button">
                            {{ __('Cancel') }}
                        </button>
                        <button class="bg-primary focus:shadow-outline inline-flex items-center rounded px-4 py-2 font-bold text-black shadow hover:bg-blue-300 focus:outline-none" type="submit">
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div wire:loading wire:target="save">
        <livewire:components.loading>
    </div>
</section>
