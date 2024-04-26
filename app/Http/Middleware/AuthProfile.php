<?php

namespace App\Http\Middleware;

use App\Models\Profile;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $authUser = auth()->user();

        // Solo Super Admin puede acceder a Empresas y Tipos de hallazgos
        if (
            Str::startsWith($request->route()->getName(), 'finding-types.')
        ) {
            return ($authUser->isSuperAdminProfile()) ? $next($request) : redirect()->route('dashboard');
        }
        return (in_array($authUser->profile_id, [
            Profile::SUPER_ADMIN,
            Profile::OWNER
        ])) ? $next($request) : redirect()->route('dashboard');
    }
}
