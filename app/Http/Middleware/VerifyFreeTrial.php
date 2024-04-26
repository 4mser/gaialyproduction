<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyFreeTrial
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // if ($request->user()->isFreeTrialExpired()) {
        //     request()->session()->flash(
        //         'error',
        //         __('Free trial expired. Please upgrade your plan.')
        //     );
        //     return redirect()->route('inspections.index');
        // }
        return $next($request);
    }
}
