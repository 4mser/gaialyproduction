<x-app-layout>
    <section>
        <livewire:components.title-bar title="{{ __('Billing') }}" />

        <section class="mx-3 rounded-md rounded-tl-md bg-white p-8 text-center shadow">
            <div class="mb-4 flex items-center justify-center text-center text-gray-500">
                <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-12 w-12">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <h1 class="mb-4 text-xl"><strong>{{ __('The payment has been approved successfully.') }}</strong></h1>

            <a class="text-gray-500 hover:underline" href="{{ route('inspections.index') }}">
                {{ __('Continue') }}
            </a>
        </section>
    </section>
</x-app-layout>
