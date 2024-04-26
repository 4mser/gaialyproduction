<?php

namespace App\Actions\Fortify;

use App\Models\Company;
use App\Models\Profile;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        $input['email'] = mb_strtolower($input['email']);
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();

        // TODO: Create company
        try {
            DB::beginTransaction();
            $company = Company::create([
                'name' => $input['company'],
            ]);

            $user =  User::create([
                'name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'profile_id' => Profile::OWNER,
                'company_id' => $company->id,
                'free_trial_expired_at' => now()->addDays(15),
                'credit_balance' => env('CREDITS_INITIAL_BALANCE', 0),
            ]);
            $user->parent_user_id = $user->id;
            $user->save();

            $company->parent_company_id = $company->id;
            $company->parent_user_id = $user->id;
            $company->save();

            User::createDefaultFindingTypes($user->id);
            
            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            throw $e;
        }
    }
}
