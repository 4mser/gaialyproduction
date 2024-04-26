<?php

namespace App\Http\Livewire\Maps\Tools;

use App\Models\Layer;
use App\Models\LayerType;
use App\Models\Operation;
// use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
// use Livewire\WithFileUploads;


class DrawLine extends Component
{

    // use WithFileUploads;

    public $name = null;
    public $file = null;
    public $iteration = 0;
    public $toggleModal = 'hidden';
    public $operationId = null;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount()
    {
        $this->operationId = session()->get('operationId');
    }

    public function render()
    {
        return view('livewire.maps.tools.draw-line');
    }
}
