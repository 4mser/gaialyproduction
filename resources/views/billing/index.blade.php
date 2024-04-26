<x-app-layout>

    <section class="flex h-screen flex-col justify-between">
        <div>
            <livewire:components.title-bar title="{{ __('Billing') }}" />
            <div class="grid gap-4 px-2 text-center sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8">
                <div class="col-span-2 flex flex-col justify-between rounded-md bg-white p-4 shadow">
                    <div class="mb-6">
                        <h2 class="mb-2 text-2xl font-bold">{{ __('Basic') }}</h2>
                    </div>
                    <div>
                        <div class="text-xl"> {{ env('PRICE_PER_CREDIT') }} {{ __('Credits') }}</div>
                        <div class="mb-6">{{ __('PER USD') }}</div>
                        <a href="{{ route('billing.form', ['plan_code' => 'basic']) }}" class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700 focus:border-gray-900 focus:outline-none focus:ring focus:ring-gray-300 active:bg-gray-900 disabled:opacity-25">
                            {{ __('Buy') }}
                        </a>
                    </div>
                </div>
                @foreach ($plans as $plan)
                    <div class="col-span-2 flex flex-col justify-between rounded-md bg-white p-4 shadow">
                        <div class="mb-6">
                            <h2 class="mb-2 text-2xl font-bold">{{ $plan->title }}</h2>
                            {{-- <div>{{ $plan->description }}</div> --}}
                        </div>
                        <div>
                            <div class="text-xl">{{ $plan->credits }} {{ __('Credits') }}</div>
                            <div class="mb-6">USD {{ round($plan->price, 0) }}</div>
                            <a href="{{ route('billing.form', ['plan_code' => $plan->code]) }}" class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700 focus:border-gray-900 focus:outline-none focus:ring focus:ring-gray-300 active:bg-gray-900 disabled:opacity-25">
                                {{ __('Buy') }}
                            </a>
                        </div>
                    </div>
                @endforeach
                <div class="col-span-2 flex flex-col justify-between rounded-md bg-white p-4 shadow">
                    <div class="mb-6">
                        <h2 class="mb-2 text-2xl font-bold">{{ __('Need more information?') }}</h2>
                    </div>
                    <div>
                        <a href="{{ route('contact-us') }}" class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700 focus:border-gray-900 focus:outline-none focus:ring focus:ring-gray-300 active:bg-gray-900 disabled:opacity-25">
                            {{ __('Contact us') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t-2 border-gray-300 bg-gray-200 p-4">
            <img class="mx-auto w-1/4" src="{{ url('img/powered-by-stripe.png') }}" />
        </div>
    </section>
</x-app-layout>
