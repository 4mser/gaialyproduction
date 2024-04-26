<x-jet-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>
    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{ photoName: null, photoPreview: null }" class="col-span-6 text-center sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" class="hidden" wire:model="photo" x-ref="photo" x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-jet-label for="photo" value="{{ __('Photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2 flex justify-center" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="h-20 w-20 rounded-full object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="col-span-1 col-start-2 col-end-3 mt-5 flex justify-between" x-show="photoPreview">
                    <span class="block h-20 w-20 rounded-full bg-cover bg-center bg-no-repeat" x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-jet-secondary-button class="mr-2 mt-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-jet-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-jet-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-jet-secondary-button>
                @endif

                <x-jet-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('First name') }}" />
            <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="state.name" autocomplete="name" />
            <x-jet-input-error for="name" class="mt-2" />
        </div>

        <!-- Last Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="last_name" value="{{ __('Last name') }}" />
            <x-jet-input id="last_name" type="text" class="mt-1 block w-full" wire:model.defer="state.last_name" />
            <x-jet-input-error for="last_name" class="mt-2" />
        </div>

        <!-- RUT-->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="rut" value="{{ __('RUT/DNI/ID') }}" />
            <x-jet-input id="rut" type="text" class="mt-1 block w-full" wire:model.defer="state.rut" />
            <x-jet-input-error for="rut" class="mt-2" />
        </div>

        <!-- Phone-->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="phone" value="{{ __('Phone') }}" />
            <x-jet-input id="phone" type="number" class="mt-1 block w-full" wire:model.defer="state.phone" />
            <x-jet-input-error for="phone" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="email" value="{{ __('Email') }}" />
            <x-jet-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="state.email" />
            <x-jet-input-error for="email" class="mt-2" />
        </div>
        <!-- Language -->
        @php $availableLocale = config('app.available_locale'); @endphp
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="locale" value="{{ __('Language') }}" />
            <select id="locale" wire:model.defer="state.locale" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @foreach ($availableLocale as $key => $item)
                    <option value="{{ $key }}" {{ app()->getLocale() == $key ? 'selected' : '' }}>{{ $item }}</option>
                @endforeach
            </select>
            <x-jet-input-error for="locale" class="mt-2" />
        </div>
        <!-- Language -->
        <!-- Profile -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="profile" value="{{ __('Profile') }}" />
            <x-jet-input id="profile" type="text" class="mt-1 block w-full bg-gray-100" disabled value="{{ auth()->user()->profile->name }}" />
        </div>
        <!-- Company -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="company" value="{{ __('Company') }}" />
            <x-jet-input id="company" type="text" class="mt-1 block w-full bg-gray-100" disabled value="{{ auth()->user()->company->name }}" />
        </div>
        <!-- Signature photo-->
        <div class="col-span-6 pb-6 sm:col-span-4">
            <x-jet-label for="signature_photo_path_new" class="mb-3 text-center" value="{{ __('Signature photo') }}" />
            @if (!empty($this->user->signature_photo_path))
                <div class="mb-3 mt-2 flex justify-center">
                    <img src="{{ '/storage/' . $this->user->signature_photo_path }}" alt="Signature photo" class="h-16">
                </div>
            @endif
            <x-jet-input id="signature_photo_path_new" type="file" class="mt-1 block w-full" wire:model.defer="state.signature_photo_path_new" />
            <span class="text-sm text-gray-400">Recommended size: 200x150</span>
            <x-jet-input-error for="signature_photo_path_new" class="mt-2" />
        </div>
        @if (auth()->user()->isOwnerProfile() ||
                auth()->user()->isSuperAdminProfile())
            <!-- Company photo -->
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="company_photo_path_new" class="mb-3 text-center" value="{{ __('Company logo') }}" />
                @if (!empty($this->user->company_photo_path))
                    <div class="mb-3 mt-2 flex justify-center">
                        <img src="{{ '/storage/' . $this->user->company_photo_path }}" alt="Company logo" class="h-16">
                    </div>
                @endif
                <x-jet-input id="company_photo_path_new" type="file" class="mt-1 block w-full" wire:model.defer="state.company_photo_path_new" />
                <span class="text-sm text-gray-400">Recommended size: 200x150</span>
                <x-jet-input-error for="company_photo_path_new" class="mt-2" />
            </div>
        @endif
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <x-jet-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
