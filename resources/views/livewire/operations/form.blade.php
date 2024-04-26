<section>
    <div class="md:grid md:grid-cols-10 md:p-2">
        <div class="md:col-span-4 md:col-start-4">
            <livewire:components.title-bar title="{{ __($title) }}" />
            <div class="w-full overflow-hidden bg-white p-4 md:rounded-lg md:shadow-md">
                <form wire:submit.prevent="save" class="">
                    @csrf
                    <div class="mb-6">
                        <x-jet-label class="text-sm" for="name" value="{{ __('Name') }}" />
                        <x-jet-input wire:model="name" type="text" class="mt-1 block w-full text-sm" />
                        <x-jet-input-error for="name" class="mt-2" />
                    </div>
                    <div class="mb-6">
                        <x-jet-label class="text-sm" for="description" value="{{ __('Description') }}" />
                        <textarea wire:model="description" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                        <x-jet-input-error for="description" class="mt-2" />
                    </div>
                    <div class="mb-6">
                        <x-jet-label class="text-sm" for="operation_type_id" value="{{ __('Inspection by Type') }}" />
                        <select wire:model="operationTypeId" name="operationTypeId" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="operation_type_id">
                            <option value="">...</option>
                            @foreach ($operationTypeOptions as $key => $operationType)
                                <option value="{{ $key }}">{{ $operationType }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if (auth()->user()->isSuperAdminProfile() ||
                            auth()->user()->isOwnerProfile())
                        <div class="mb-6">
                            <x-jet-label class="text-sm" for="company_id" value="{{ __('Company') }}" />
                            <select wire:model="companyId" name="companyId" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="operation_type_id">
                                <option value="">...</option>
                                @foreach ($companyOptions as $key => $company)
                                    <option value="{{ $key }}">{{ $company }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div>
                        <button class="bg-primary focus:shadow-outline float-right rounded px-4 py-2 text-sm font-bold text-black shadow hover:bg-blue-300 focus:outline-none" type="submit">
                            {{ __('Save') }}
                        </button>
                        <button wire:click="back" class="focus:shadow-outline float-right mx-2 rounded bg-gray-500 px-4 py-2 text-sm font-bold text-white shadow hover:bg-gray-700 focus:outline-none" type="button">
                            {{ __('Cancel') }}
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
