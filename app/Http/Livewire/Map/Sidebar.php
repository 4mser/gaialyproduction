<?php

namespace App\Http\Livewire\Map;

use App\Models\Operation;
use Livewire\Component;

class Sidebar extends Component
{

    public Operation $operation;

    public function render()
    {
        return view('livewire.map.sidebar');
    }
}
