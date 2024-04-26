<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;

class TitleBar extends Component
{

    public $title = '';

    public function render()
    {
        return view('livewire.components.title-bar', [
            'title' => $this->title,
        ]);
    }
}
