<?php

namespace App\Http\Livewire\Maps\Tools;

use Livewire\Component;

class Report extends Component
{
    public function mount()
    {
        $this->operationId = session()->get('operationId');
    }

    public function render()
    {
        return view('livewire.maps.tools.report');
    }
}
