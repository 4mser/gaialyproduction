<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">

            <div class="flex flex-col text-center">
                <x-jet-authentication-card-logo />
                <div class="mt-1 text-lg font-bold">{{ env('APP_NAME') }}</div>
            </div>
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="relative mb-4 rounded border border-red-400 bg-red-100 px-3 py-2 text-sm text-red-700">{{ session('status') }}</div>
        @endif

        @if (session('oauth-error'))
            <div class="relative mb-4 rounded border border-red-400 bg-red-100 px-3 py-2 text-sm text-red-700">{{ session('oauth-error') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-6">
                <p class="text-primary text-2xl font-bold">{{ __('Login') }}</p>
                <p class="mt-2 text-sm text-gray-400">{{ __('Enter your details below to continue') }}</p>
            </div>

            <div class="text-gray-500">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="mt-1 block w-full text-gray-500" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="mt-1 block w-full text-gray-500" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="mt-4 block">
                <label for="remember_me" class="flex items-center">
                    <x-jet-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-500">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="mt-4">
                @if (Route::has('password.request'))
                    <a class="text-sm text-gray-500 hover:text-gray-600" href="{{ route('password.request') }}">
                        {{ __('Forgot Password') }}
                    </a>
                @endif
            </div>
            <div class="mt-6 flex justify-center">
                <x-jet-button class="bg-primary ml-4">
                    {{ __('Log in') }}
                </x-jet-button>
            </div>
            <div class="mt-6 flex justify-center text-sm text-gray-400">
                {{ __("Don't have an account?") }} &nbsp<a class="text-primary" href="/register">{{ __('Sign up here') }}</a>
            </div>
            <div class="mt-5 flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div class="h-[1px] w-full bg-gray-300"></div>
                    <span class="mx-6 text-sm uppercase text-gray-400">{{ __('Or') }}</span>
                    <div class="h-[1px] w-full bg-gray-300"></div>
                </div>

                <div class="text-sm">
                    <a href="{{ route('login.google') }}" class="my-2 flex items-center justify-center space-x-2 rounded bg-gray-100 py-2 text-gray-600 hover:bg-gray-200">
                        <svg class="h-5 w-5" xmlns="https://www.w3.org/2000/svg" viewBox="0 0 326667 333333" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd">
                            <path d="M326667 170370c0-13704-1112-23704-3518-34074H166667v61851h91851c-1851 15371-11851 38519-34074 54074l-311 2071 49476 38329 3428 342c31481-29074 49630-71852 49630-122593m0 0z" fill="#4285f4"></path>
                            <path d="M166667 333333c44999 0 82776-14815 110370-40370l-52593-40742c-14074 9815-32963 16667-57777 16667-44074 0-81481-29073-94816-69258l-1954 166-51447 39815-673 1870c27407 54444 83704 91852 148890 91852z" fill="#34a853"></path>
                            <path d="M71851 199630c-3518-10370-5555-21482-5555-32963 0-11482 2036-22593 5370-32963l-93-2209-52091-40455-1704 811C6482 114444 1 139814 1 166666s6482 52221 17777 74814l54074-41851m0 0z" fill="#fbbc04"></path>
                            <path d="M166667 64444c31296 0 52406 13519 64444 24816l47037-45926C249260 16482 211666 1 166667 1 101481 1 45185 37408 17777 91852l53889 41853c13520-40185 50927-69260 95001-69260m0 0z" fill="#ea4335"></path>
                        </svg>
                        <span>{{ __('Sign in with Google') }}</span>
                    </a>
                    <a href="{{ route('login.linkedin') }}" class="my-2 flex items-center justify-center space-x-2 rounded bg-gray-100 py-2 text-gray-600 hover:bg-gray-200">
                        <svg class="h-5 w-5" xmlns="https://www.w3.org/2000/svg" viewBox="0 0 122.88 122.31">
                            <defs>
                                <style>
                                    .cls-1 {
                                        fill: #0a66c2;
                                    }

                                    .cls-1,
                                    .cls-2 {
                                        fill-rule: evenodd;
                                    }

                                    .cls-2 {
                                        fill: #fff;
                                    }
                                </style>
                            </defs>
                            <title>linkedin-app</title>
                            <path class="cls-1" d="M27.75,0H95.13a27.83,27.83,0,0,1,27.75,27.75V94.57a27.83,27.83,0,0,1-27.75,27.74H27.75A27.83,27.83,0,0,1,0,94.57V27.75A27.83,27.83,0,0,1,27.75,0Z"></path>
                            <path class="cls-2" d="M49.19,47.41H64.72v8h.22c2.17-3.88,7.45-8,15.34-8,16.39,0,19.42,10.2,19.42,23.47V98.94H83.51V74c0-5.71-.12-13.06-8.42-13.06s-9.72,6.21-9.72,12.65v25.4H49.19V47.41ZM40,31.79a8.42,8.42,0,1,1-8.42-8.42A8.43,8.43,0,0,1,40,31.79ZM23.18,47.41H40V98.94H23.18V47.41Z"></path>
                        </svg>
                        <span>{{ __('Sign in with LinkedIn') }}</span>
                    </a>
                </div>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
