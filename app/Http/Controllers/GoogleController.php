<?php

namespace App\Http\Controllers;

use App\Models\OauthService;
use App\Models\Profile;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Database\DBAL\TimestampType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class GoogleController extends Controller
{
    public function login()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $driver = 'google';

        DB::beginTransaction();
        try {

            $oauth = Socialite::driver($driver)->user();


            $oauthService = OauthService::where('oauth_id', $oauth->user['id'])->where('oauth_service', $driver)->first();
            if ($oauthService) {
                $user = User::findOrfail($oauthService->user_id);
            } else {
                $user = User::where('email', $oauth->user['email'])->first();
                if (!$user) {
                    $companyName  = $oauth->user['given_name'] . ' ' . $oauth->user['family_name'] . "'s Company";
                    $input = [
                        'name' => $oauth->user['given_name'],
                        'last_name' => $oauth->user['family_name'],
                        'email' => $oauth->user['email'],
                        'password' => Hash::make(time() . $oauth->user['email']),
                        'profile_id' => Profile::OWNER,
                        'free_trial_expired_at' => now()->addDays(15),
                        'credit_balance' => env('CREDITS_INITIAL_BALANCE', 0),
                        'email_verified_at' => now(),
                        'company_name' => $companyName,
                    ];
                    $user = User::signup($input);
                }
                $oauthService = OauthService::create([
                    'oauth_service' => $driver,
                    'oauth_id' => $oauth->user['id'],
                    'user_id' => $user->id,
                ]);
            }

            if ($user->is_active !== true) {
                throw new Exception('User ' . $user->email . 'is not active.');
            }

            Auth::loginUsingId($user->id);
            DB::commit();
            return redirect()->route('dashboard');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->route('login')->with('oauth-error', __('An error occurred while signing in with Google, please try again later.'));
        }
    }
}
