<?php

namespace App\Http\Livewire\Maps\Tools;

use App\Models\Layer;
use App\Models\LayerType;
use App\Models\Operation;
use App\Rules\KmlFile;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use ZipArchive;

class UploadKml extends Component
{

    use WithFileUploads;

    public $name = null;
    public $file = null;
    public $iteration = 0;
    public $toggleModal = 'hidden';
    public $operationId = null;

    public Operation $operation;

    public function mount()
    {
        $this->operationId = session()->get('operationId');
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.maps.tools.upload-kml');
    }

    public function setToggleModal()
    {
        $this->name = null;
        $this->file = null;
        $this->iteration++;
        $this->toggleModal = ($this->toggleModal == 'hidden') ? '' : 'hidden';
    }

    public function upload()
    {

        $this->validate(
            [
                'name' => 'required',
                'file' => ['required', 'file', new KmlFile],
            ],
            [
                'required' => __('This field is required.'),
            ]
        );

        DB::beginTransaction();
        try {
            $layer = Layer::create([
                'name' => $this->name,
                'operation_id' => $this->operationId,
                'geom' => json_encode('[]'),
                'layer_type_id' => LayerType::KML,
                'user_id' => auth()->user()->id,
            ]);
            $pathinfo = pathinfo($this->file->getClientOriginalName());
            $extension = $pathinfo['extension'];

            if ($extension == 'kmz') {
                // KMZ
                $zip = new ZipArchive();
                $res = $zip->open($this->file->getRealPath());
                if (!$res)
                    throw new Exception(__('Error while trying to upload KML/KMZ File'));

                if ($zip->numFiles == 0)
                    throw new Exception(__('The KMZ file is empty.'));

                $kmzPath = uploads_path('kmz/' . $layer->id);
                $res = $zip->extractTo($kmzPath);
                if (!$res) throw new Exception(__('Error while trying to upload KML/KMZ File'));

                $kml = file_get_contents($kmzPath . '/doc.kml');
                file_put_contents($kmzPath . '/doc.kml', str_replace('<href>', '<href>storage/kmz/' . $layer->id . '/', $kml));

                $layer->file_name = 'kmz/' . $layer->id . '/doc.kml';

            } else {
                // KML
                $layer->file_name = $this->file->storeAs(
                    'kml',
                    $layer->id . '.' . $extension,
                    'uploads'
                );
            }
            $layer->file_size = $this->file->getSize();
            $layer->file_extension = 'kml';
            $layer->save();

            DB::commit();
            $this->setToggleModal();
            $this->dispatchBrowserEvent('toast', [
                'type' => 'success',
                'message' => __('The KML/KMZ File was uploaded succesfully.')
            ]);
            $this->emit("getGeom", [
                'id' => $layer->id,
                'route' => $layer->file_name
            ]);
        } catch (Exception $ex) {
            DB::rollback();
            Log::error($ex);
            $this->dispatchBrowserEvent('toast', [
                'type' => 'error',
                'message' => __('Error while trying to upload KML/KMZ File')
            ]);
        }
    }

    public function updateData($id, $data)
    {
        $layer = Layer::find($id);
        $layer->geom = $data;
        $layer->save();
        $this->emit("reload_kml", $data);
    }
}
