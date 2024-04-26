<?php

namespace App\Http\Livewire\Maps\Tools;

use App\Models\Layer;
use App\Models\LayerType;
use Error;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Layers extends Component
{
    public $layers = [];
    public $layerId = null;
    public $toggleDeleteModal = 'hidden';
    public $toggleModal = 'hidden';

    public $openModal = false;

    public $tab = LayerType::IMAGE;

    public $typeImage = LayerType::IMAGE;
    public $typeKml = LayerType::KML;
    public $typeTif = LayerType::ORTHOPHOTO;
    public $typeShp = LayerType::SHAPEFILE;
    public $typeThermo = LayerType::THERMO;
    public $typeDrawn = LayerType::DRAWN;
    public $operationId = null;

    protected $listeners = [
        'openLayerListModal' => 'openModal',
        'deleteLayer' => 'delete'
    ];

    public function mount()
    {
        $this->setlayers();
        $this->operationId = session()->get('operationId');
    }

    public function render()
    {
        return view('livewire.maps.tools.layers');
    }

    public function setLayers()
    {
        $this->operationId = session()->get('operationId');
        $layer_types = [
            $this->typeImage,
            $this->typeKml,
            $this->typeTif,
            $this->typeShp,
            $this->typeThermo,
            $this->typeDrawn,
        ];
        foreach ($layer_types as $layer_type_id) {
            $this->setLayer($layer_type_id);
        }
    }
    public function setLayer($layerType)
    {
        if ($layerType == LayerType::IMAGE) {
            $this->layers[$layerType] = Layer::where('operation_id', $this->operationId)->whereIn('layer_type_id', [$layerType, LayerType::THERMO])->orderBy('name')->get();
        } else {
            $this->layers[$layerType] = Layer::where('operation_id', $this->operationId)->where('layer_type_id', $layerType)->orderBy('name')->get();
        }
    }
    public function setToggleModal()
    {
        $this->toggleModal = ($this->toggleModal == 'hidden') ? '' : 'hidden';
    }

    public function setToggleDeleteModal()
    {
        $this->toggleDeleteModal = ($this->toggleDeleteModal == 'hidden') ? '' : 'hidden';
    }

    public function setTab($tab)
    {
        $this->tab = $tab;
    }

    public function delete($layerId)
    {
        if (auth()->user()->isFreeTrialExpired()) {
            $this->dispatchBrowserEvent('toast', [
                'type' => 'success',
                'message' => __('Free trial expired. Please upgrade your plan.')
            ]);
            return true;
        }

        DB::beginTransaction();
        try {
            $layer = Layer::where('id', $layerId)->where('operation_id', $this->operationId)->first();
            $layer->delete();
            DB::commit();
            $this->setLayer($layer->layer_type_id);
            $this->dispatchBrowserEvent('toast', [
                'type' => 'success',
                'message' => __('The layer has been successfully removed.')
            ]);
            $this->emit('reload_map');
        } catch (Exception | Error $ex) {
            Log::error($ex);
            DB::rollback();
            $this->dispatchBrowserEvent('toast', [
                'type' => 'error',
                'message' => __('Error trying to delete layer.')
            ]);
        }
    }

    public function cancelDelete()
    {
        $this->setToggleDeleteModal();
        $this->layerId = null;
    }

    public function openModal()
    {
        $this->openModal = true;
    }
}
