<?php

namespace App\Http\Livewire\Operations;

use Livewire\Component;

class DemoButton extends Component
{
    protected $listeners = ['createDemo' => 'create'];

    public function render()
    {

        return view('livewire.operations.demo-button');
    }

    public function create()
    {
        $seeder = new \Database\Seeders\DemoDataSeeder();
        $seeder->run();

        request()->session()->flash(
            'success',
            __('The demo inspection has been successfully created')
        );
        redirect()->route('inspections.index');
    }
}
