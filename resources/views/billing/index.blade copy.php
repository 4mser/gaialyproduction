<x-app-layout>

    <section>
        <livewire:components.title-bar title="{{ __('Billing') }}" />

        <div class="lg:grid lg:grid-cols-12 lg:gap-4 lg:p-2">
            <div class="lg:col-span-4">
                <div class="overflow-hidden bg-white p-4 md:rounded-lg md:shadow-md">
                    <h4 class="mb-4">Price per credit: {{ $creditPrice }} USD</h4>
                    <form action="{{ route('billing.checkout') }}" method="POST" id="checkout-form">
                        @csrf
                        <div class="mb-6">
                            <x-jet-label class="text-xs" for="credits" value="{{ __('Credits') }}" />
                            <x-jet-input type="number" name="credits" id="credits" class="mt-1 block w-full bg-gray-100 text-xs" min="0" value="{{ old('credits') }}" readonly />
                            <x-jet-input-error for="credits" id="credits-error" class="mt-1 text-xs text-red-600" />
                            <div id="credits-error" class="mt-1 text-xs text-red-600"></div>
                        </div>
                        <div class="mb-6">
                            <x-jet-label class="text-xs" for="amount" value="{{ __('Amount') }} (USD)" />
                            <x-jet-input type="number" name="amount" id="amount" class="mt-1 block w-full text-xs" min="0" value="{{ old('amount') }}" required />
                            <x-jet-input-error for="amount" id="amount-error" class="mt-1 text-xs text-red-600" />
                            <div id="amount-error" class="mt-1 text-xs text-red-600"></div>
                        </div>
                        <div class="mb-6">
                            <x-jet-label class="text-xs" for="name-on-card" value="{{ __('Name on card') }}" />
                            <x-jet-input type="text" name="name_on_card" id="name-on-card" class="mt-1 block w-full text-xs" value="{{ old('name_on_card') }}" required />
                            <x-jet-input-error for="name_on_card" class="mt-1 text-xs text-red-600" />
                            <div id="name-on-card-error" class="mt-1 text-xs text-red-600"></div>
                        </div>
                        <div class="card-elements mb-6">
                            <div class="form-row">
                                <x-jet-label for="card-element" value="{{ __('Card information') }}" />
                                <div id="card-element" class="form-control"></div>
                                <!-- Used to display form errors. -->
                                <div id="card-errors" class="mt-1 text-xs text-red-600" role="alert"></div>
                            </div>
                            <div class="stripe-errors text-xs text-red-600"></div>
                        </div>

                        <div class="mt-4 flex items-center justify-end text-xs">
                            <button id="card-button" data-secret="{{ $intent->client_secret }}" class="bg-primary focus:shadow-outline inline-flex items-center rounded py-2 px-4 font-bold text-black shadow hover:bg-blue-300 focus:outline-none" type="submit">
                                {{ 'Pay' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>


    @push('styles')
        <style>
            .StripeElement {
                --tw-border-opacity: 1;
                border: 1px solid rgb(209 213 219 / var(--tw-border-opacity));
                border-radius: 0.375rem;
                --tw-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
                --tw-shadow-colored: 0 1px 2px 0 var(--tw-shadow-color);
                box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
                appearance: none;
                background-color: #fff;
                padding: 0.5rem;
                margin-top: 6px;
            }
        </style>
    @endpush


    @push('scripts')
        <script src="http://js.stripe.com/v3/"></script>
        <script>
            @if (request()->has('res') && request()->get('res') == 'success')
                toast("success", "{{ __('Payment successful!') }}");
            @endif
            @if (request()->has('res') && request()->get('res') == 'error')
                alert("{{ __('Error trying to process payment.') }}", 'error');
            @endif
            const CREDIT_PRICE = {{ $creditPrice }}
            let stripe = Stripe('{{ env('STRIPE_KEY') }}');
            let elements = stripe.elements();
            let style = {
                base: {
                    fontSize: '16px',
                },
            };
            let card = elements.create('card', {
                hidePostalCode: true,
                style: style
            });
            card.mount('#card-element');
            card.addEventListener('change', function(event) {
                let displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            const credits = document.getElementById('credits');
            const creditsError = document.getElementById('credits-error');

            const amount = document.getElementById('amount');
            const amountError = document.getElementById('amount-error');

            const nameOnCard = document.getElementById('name-on-card');
            const nameOnCardError = document.getElementById('name-on-card-error');

            const cardButton = document.getElementById('card-button');
            const clientSecret = cardButton.dataset.secret;

            amount.addEventListener('keyup', (e) => {
                credits.value = calculateCredits(amount.value);
            });

            cardButton.addEventListener('click', async (e) => {
                e.preventDefault();
                let cardButtonTextContent = cardButton.textContent;
                cardButton.textContent = 'Sending...';
                cardButton.disabled = 'disabled';
                creditsError.textContent = '';
                amountError.textContent = '';
                nameOnCardError.textContent = '';

                if (credits.value.trim() == '')
                    creditsError.textContent = 'This field is required.';

                if (amount.value.trim() == '')
                    amountError.textContent = 'This field is required.';

                if (nameOnCard.value.trim() == '')
                    nameOnCardError.textContent = 'This field is required.';

                const {
                    setupIntent,
                    error
                } = await stripe.confirmCardSetup(
                    clientSecret, {
                        payment_method: {
                            card: card,
                            billing_details: {
                                name: nameOnCard.value
                            }
                        }
                    }
                );
                console.log(error);
                if (error) {
                    let errorElement = document.getElementById('card-errors');
                    errorElement.textContent = error.message;
                    cardButton.textContent = cardButtonTextContent;
                    cardButton.disabled = '';
                } else {
                    paymentMethodHandler(setupIntent.payment_method);
                }
            });

            function paymentMethodHandler(payment_method) {
                let form = document.getElementById('checkout-form');
                let hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'payment_method');
                hiddenInput.setAttribute('value', payment_method);
                form.appendChild(hiddenInput);
                form.submit();
            }

            function calculateCredits(amount) {
                let credits = amount * CREDIT_PRICE;
                return credits;
            }
        </script>
    @endpush
</x-app-layout>
