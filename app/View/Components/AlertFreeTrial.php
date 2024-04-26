<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AlertFreeTrial extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $freeTrialExpiredAt = auth()->user()->free_trial_expired_at;
        return view('components.alert-free-trial', compact('freeTrialExpiredAt'));
    }
}
