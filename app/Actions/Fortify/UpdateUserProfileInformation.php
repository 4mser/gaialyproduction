<?php

namespace App\Actions\Fortify;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        $input['email'] = mb_strtolower($input['email']);
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'rut' => ['string', 'max:10'],
            'phone' => ['string', 'max:12'],
            'company' => ['string', 'max:255'],
            'locale' => ['string', Rule::in(array_keys(config('app.available_locale')))],
            'signature_photo_path_new' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'company_photo_path_new' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ], [
            'signature_photo_path_new.max' => 'The signature photo must not be greater than :max kilobytes.',
            'company_photo_path_new.max' => 'The company logo must not be greater than :max kilobytes.',
            'rut.required' => 'The RUT/DNI/ID field is required.',
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if (isset($input['signature_photo_path_new'])) {
            $signatureFilename = Str::uuid() . '.' . $input['signature_photo_path_new']->extension();
            $input['signature_photo_path_new']->storeAs('signature-photos', $signatureFilename);
            $user->signature_photo_path = 'signature-photos/' . $signatureFilename;
        }

        if (isset($input['company_photo_path_new'])) {
            $companyFilename = Str::uuid() . '.' . $input['company_photo_path_new']->extension();
            $input['company_photo_path_new']->storeAs('company-photos', $companyFilename);
            $user->company_photo_path = 'company-photos/' . $companyFilename;
        }

        if (
            $input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail
        ) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'last_name' => $input['last_name'],
                'email' => $input['email'],
                'rut' => $input['rut'],
                'phone' => $input['phone'],
            ])->save();
            if ($user->locale !== $input['locale']) {
                $user->locale = $input['locale'];
                $user->save();
                app()->setLocale($user->locale);
                redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
            }
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function updateVerifiedUser($user, array $input)
    {
        $user->forceFill([
            'name' => $input['name'],
            'last_name' => $input['last_name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
