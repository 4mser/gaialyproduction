@props(['submit'])

<div {{ $attributes->merge(['class' => 'md:grid md:grid-cols-2 md:gap-6']) }}>
    
    <div class="mt-5 md:mt-0 md:col-span-2">
        <form wire:submit.prevent="{{ $submit }}">
            <div class="px-4 py-5 bg-white sm:p-6 shadow {{ isset($actions) ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-md' }}">
                <div class="mb-10">
                    <x-jet-section-title>
                        <x-slot name="title">{{ $title }}</x-slot>
                        <x-slot name="description">{{ $description }}</x-slot>
                    </x-jet-section-title>
                </div>
                <div class="grid gap-6">
                    {{ $form }}
                </div>
                @if (isset($actions))
                    <div class="flex items-center justify-end py-1 mt-4 text-right sm:rounded-bl-md sm:rounded-br-md">
                        {{ $actions }}
                    </div>
                @endif
            </div>

        </form>
    </div>
</div>
