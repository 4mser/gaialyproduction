<?php

namespace App\Http\Livewire\Images;

use App\Models\Layer;
use App\Models\LayerType;
use Livewire\Component;

class Index extends Component
{
    public $images = [];

    public function mount()
    {
        if (session('operationId')) {
            $this->images = Layer::whereIn('layer_type_id', [LayerType::IMAGE, LayerType::THERMO])
                ->where('operation_id', session('operationId'))
                ->where('visible', true)
                ->orderBy('created_at', 'desc')
                ->get();
        }
    }
    public function render()
    {
        // TODO: Obtener todas las imagenes de una operacion
        return view('livewire.images.index');
    }
}
