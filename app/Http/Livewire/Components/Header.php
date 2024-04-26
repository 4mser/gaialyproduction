<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;

class Header extends Component
{

    public $title = '';

    public function render()
    {

        return view('livewire.components.header', [
            'title' => $this->title,
        ]);
    }
}
