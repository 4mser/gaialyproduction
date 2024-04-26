<section>
    <div class="md:grid md:grid-cols-10 md:p-2">
        <div class="md:col-span-4 md:col-start-4">
            <livewire:components.title-bar title="{{ __('Contact Us') }}" />
            <div class="w-full overflow-hidden bg-white p-4 md:rounded-lg md:shadow-md">
                <form>
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm text-sm font-medium text-gray-700" for="name">
                            {{ __('Enter your name') }}
                        </label>
                        <input wire:model="name" type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 disabled:bg-gray-100">
                        <x-jet-input-error for="name" class="mt-1 text-xs" />
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm text-sm font-medium text-gray-700" for="email">
                            {{ __('Enter a valid email address') }}
                        </label>
                        <input wire:model="email" type="text" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 disabled:bg-gray-100">
                        <x-jet-input-error for="email" class="mt-1 text-xs" />
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm text-sm font-medium text-gray-700" for="message">
                            {{ __('Enter your message') }}
                        </label>
                        <textarea wire:model="message" name="message" id="message" rows="6" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 disabled:bg-gray-100"></textarea>
                        <x-jet-input-error for="message" class="mt-1 text-xs" />
                    </div>
                    <div>
                        <button wire:click="send" type="button" class="bg-primary focus:shadow-outline float-right rounded py-2 px-4 text-sm font-bold text-black shadow hover:bg-blue-300 focus:outline-none disabled:bg-blue-200 disabled:text-gray-400 disabled:focus:bg-blue-200">
                            {{ __('Send') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
