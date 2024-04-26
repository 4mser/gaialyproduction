<?php

namespace App\Http\Livewire\Users;

use Livewire\Component;

class AvailableBalance extends Component
{
    protected $listeners = ['renderBalanceComponent' => 'render'];

    public function render()
    {
        $balance = auth()->user()->getCreditBalance();
        $balance = ($balance > 0) ? $balance . ' ' . __('credits') : __('No balance');
        return view('livewire.users.available-balance', compact('balance'));
    }
}
