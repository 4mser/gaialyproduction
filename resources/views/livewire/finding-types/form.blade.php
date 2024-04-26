<section>
    <livewire:components.title-bar title="{{ __('Finding Type') }}" />
    <div class="md:p-2 md:grid md:grid-cols-10">
        <div class="md:col-start-4 md:col-span-4">
            <div class="w-full md:max-w-md p-4 bg-white md:shadow-md overflow-hidden md:rounded-lg">
                <form wire:submit.prevent="save" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                        <x-jet-label for="name" value="{{ __('Name') }}" />
                        <x-jet-input wire:model="name" type="text" class="mt-1 block w-full" />
                        <x-jet-input-error for="name" class="mt-2" />
                    </div>
                    <div class="mb-6">
                        <x-jet-label for="name" value="{{ __('Price') }}" />
                        <div class="flex">
                            <input
                                class="mt-1 block w-8/12 rounded-md rounded-r-none border-gray-300 text-sm shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                type="text" wire:model="price">
                            <select wire:model="currency"
                                class="mt-1 block w-4/12 rounded-md rounded-l-none border-l-0 border-gray-300 text-sm shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @foreach ($currencies as $cur)
                                    <option value="{{ $cur->code }}" @if ($cur->code == $currency)
                                        selected
                                        
                                    @endif>{{ $cur->code }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-jet-input-error for="price" class="mt-2" />
                    </div>
                    <div class="mb-6">
                        <x-jet-label for="parentFindingType" value="{{ __('Parent Finding Type') }}" />
                        <div class="flex">
                            <select wire:model="parentFindingType"
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ __('None') }}</option>
                                @foreach ($findingTypes as $type)
                                    <option value="{{ $type->id }}" @if ($type->id == $parentFindingType)
                                        selected
                                    @endif>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-jet-input-error for="parentFindingType" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <button wire:click="back"
                            class="inline-flex items-center bg-gray-500 hover:bg-gray-700 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded mx-2"
                            type="button">
                            {{ "Cancelar" }}
                        </button>
                        <button
                            class="inline-flex items-center shadow bg-blue-200  text-black hover:bg-blue-300 focus:shadow-outline focus:outline-none font-bold py-2 px-4 rounded"
                            type="submit">
                            {{ "Guardar" }}
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