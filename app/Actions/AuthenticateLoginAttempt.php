<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticateLoginAttempt
{
    public function __invoke(Request $request)
    {
        $user = User::where('email', mb_strtolower($request->email))
            ->whereIsActive(true)
            ->first();

        if (
            $user &&
            Hash::check($request->password, $user->password)
        ) {
            return $user;
        }
    }
}
