<?php

namespace App\Http\Livewire\Map;

use App\Models\Operation;
use Livewire\Component;
use \App\Models\Layer;
use App\Models\LayerType;
use Exception;
use Illuminate\Support\Facades\Log;

class Map extends Component
{
    public $layers;
    public $layer;
    public $tmpName;
    public $tmpDescription;
    public $tmpGeom;
    public $tmpFillColor = "#3388ff";
    public $tmpBorderColor = "#3388ff";
    public $tmpOpacity = 1;
    public $toggleModal = 'hidden';

    public $typeImage = LayerType::IMAGE;
    public $typeKML = LayerType::KML;
    public $typeSHP = LayerType::SHAPEFILE;
    public $typeTif = LayerType::ORTHOPHOTO;
    public $typeDrawn = LayerType::DRAWN;
    public $typeThermo = LayerType::THERMO;

    public $newLayer;

    public $operation = null;

    public $authUser = null;

    public $tilesPath = '';

    public function mount()
    {
        try {
            $this->tilesPath = uploads_path();
            $operationId = session()->get('operationId');
            $this->operation = Operation::findByProfile($operationId);
        } catch (Exception $e) {
            Log::error($e);
            request()->session()->flash(
                'error',
                __('Error al intentar abrir el mapa.')
            );
        }
        if (is_null($this->operation))
            return redirect()->route('inspections.index');
    }

    public function render()
    {
        return view('livewire.map.map');
    }

    public function setToggleModal($title = '')
    {
        $this->toggleModal = ($this->toggleModal == 'hidden') ? '' : 'hidden';
    }

    public function saveLayer()
    {
        $this->validate([
            "tmpName" => "required"
        ], [
            "tmpName.required" => __("Please enter a name for the layer")
        ]);

        $this->layer['name'] = $this->tmpName;
        $this->layer['description'] = $this->tmpDescription;
        $this->layer['geom'] = $this->tmpGeom;
        $this->layer['symbology'] = json_encode(array("fillColor" => $this->tmpFillColor, "color" => $this->tmpBorderColor, "opacity" => $this->tmpOpacity));
        $this->layer['layer_type_id'] = $this->typeDrawn;
        $this->layer['operation_id'] = $this->operation->id;
        $this->layer['user_id'] = Auth()->user()->id;

        $this->newLayer = Layer::create($this->layer);

        $this->setToggleModal();
        $this->dispatchBrowserEvent('toast', [
            'type' => 'success',
            'message' => __('The item has been successfully saved.')
        ]);
        $this->emit("reload_drawn", $this->tmpGeom);
    }


    public function getLayers()
    {
        $this->emit("getLayers", $this->operation->layers);
    }
}
