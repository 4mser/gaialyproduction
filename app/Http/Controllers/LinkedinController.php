<?php

namespace App\Http\Controllers;

use App\Models\OauthService;
use App\Models\Profile;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LinkedinController extends Controller
{
    public function login()
    {

        $state = bin2hex(random_bytes(32));
        session(['linkedin_state' => $state]);

        $queryParams = http_build_query([
            'response_type' => 'code',
            'client_id' => config('services.linkedin.client_id'),
            'redirect_uri' => config('services.linkedin.redirect'),
            'state' => $state,
            'scope' => 'openid,profile,email',
        ]);
        $url = 'http://www.linkedin.com/oauth/v2/authorization?' . $queryParams;
        return redirect($url);
    }

    public function callback(Request $request)
    {
        $driver = 'linkedin';

        try {
            DB::beginTransaction();
            $linkedinState = $request->session()->get('linkedin_state');
            $state = $request->input('state');
            if ($linkedinState == $state) {
                $code = $request->input('code');
                $http = new Client();
                $response = $http->post('http://www.linkedin.com/oauth/v2/accessToken', [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'form_params' => [
                        'grant_type' => 'authorization_code',
                        'code' => $code,
                        'client_id' => config('services.linkedin.client_id'),
                        'client_secret' => config('services.linkedin.client_secret'),
                        'redirect_uri' => config('services.linkedin.redirect'),
                    ],
                ]);
                $response = json_decode($response->getBody()->getContents(), true);
                $accessToken = $response['access_token'];

                $response = $http->get('http://api.linkedin.com/v2/userinfo', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                    ],
                ]);
                $oauth = json_decode($response->getBody()->getContents(), true);

                if ($oauth['email_verified']) {
                    $oauthService = OauthService::where('oauth_id', $oauth['sub'])->where('oauth_service', $driver)->first();
                    if ($oauthService) {
                        $user = User::findOrfail($oauthService->user_id);
                    } else {
                        $user = User::where('email', $oauth['email'])->first();

                        if (!$user) {
                            $companyName  = $oauth['given_name'] . ' ' . $oauth['family_name'] . "'s Company";
                            $input = [
                                'name' => $oauth['given_name'],
                                'last_name' => $oauth['family_name'],
                                'email' => $oauth['email'],
                                'password' => Hash::make(time() . $oauth['email']),
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
                            'oauth_id' => $oauth['sub'],
                            'user_id' => $user->id,
                        ]);
                    }

                    if ($user->is_active !== true) {
                        throw new Exception('User ' . $user->email . 'is not active.');
                    }

                    Auth::loginUsingId($user->id);
                    DB::commit();
                    return redirect()->route('dashboard');
                } else {
                    return redirect()->route('login')->with('oauth-error', _('Your email is not verified in Linkedin.'));
                }
            } else {
                throw new Exception('Unauthorized: state is invalid.', 401);
            }
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->route('login')->with('oauth-error', __('An error occurred while signing in with Linkedin, please try again later.'));
        }
    }
}
