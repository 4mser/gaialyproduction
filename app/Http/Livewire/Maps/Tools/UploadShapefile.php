<?php

namespace App\Http\Livewire\Maps\Tools;

use App\Models\Layer;
use App\Models\LayerType;
use App\Models\Operation;
use App\Rules\DbfFile;
use App\Rules\PrjFile;
use App\Rules\ShpFile;
use App\Rules\ShxFile;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadShapefile extends Component
{

    use WithFileUploads;

    public $name = null;
    public $file_shp = null;
    public $file_shx = null;
    public $file_prj = null;
    public $file_dbf = null;
    public $iteration = 0;
    public $toggleModal = 'hidden';
    public $tmpFillColor = "#3388ff";
    public $tmpBorderColor = "#3388ff";
    public $tmpField = null;
    public $tmpOpacity = 1;
    public $operationId = null;


    public Operation $operation;

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
        return view('livewire.maps.tools.upload-shapefile');
    }

    public function setToggleModal()
    {
        $this->name = null;
        $this->file_shp = null;
        $this->file_shx = null;
        $this->file_dbf = null;
        $this->file_prj = null;
        $this->iteration++;
        $this->toggleModal = ($this->toggleModal == 'hidden') ? '' : 'hidden';
    }

    public function upload()
    {
        $this->validate(
            [
                'name' => 'required',
                'file_shp' => ['required', 'file', new ShpFile],
                'file_shx' => ['required', 'file', new ShxFile],
                'file_prj' => ['required', 'file', new PrjFile],
                'file_dbf' => ['required', 'file', new DbfFile],
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
                'geom' => json_encode('[]'), // TODO: por definir
                'symbology' => json_encode(array("fillColor" => $this->tmpFillColor, "color" => $this->tmpBorderColor, "opacity" => $this->tmpOpacity, "field" => $this->tmpField)), // TODO: por definirF
                'layer_type_id' => LayerType::SHAPEFILE,
                'user_id' => auth()->user()->id,
            ]);

            $folder = 'uploads/shapefile';

            // SHP
            $pathinfo_shp = pathinfo($this->file_shp->getClientOriginalName());
            $extension_shp = $pathinfo_shp['extension'];
            $file_name = $this->file_shp->storeAs(
                $folder,
                $layer->id . '.' . $extension_shp,
                'uploads'
            );
            // SHX
            $pathinfo_shx = pathinfo($this->file_shx->getClientOriginalName());
            $extension_shx = $pathinfo_shx['extension'];
            $this->file_shx->storeAs(
                $folder,
                $layer->id . '.' . $extension_shx,
                'uploads'
            );
            // DBF
            $pathinfo_dbf = pathinfo($this->file_dbf->getClientOriginalName());
            $extension_dbf = $pathinfo_dbf['extension'];
            $this->file_dbf->storeAs(
                $folder,
                $layer->id . '.' . $extension_dbf,
                'uploads'
            );
            // PRJ
            $pathinfo_prj = pathinfo($this->file_prj->getClientOriginalName());
            $extension_prj = $pathinfo_prj['extension'];
            $this->file_prj->storeAs(
                $folder,
                $layer->id . '.' . $extension_prj,
                'uploads'
            );

            // GEOJSON
            $input = Storage::disk('uploads')->path($file_name);
            $output = Storage::disk('uploads')->path($folder . '/' . $layer->id . '.geojson');
            $command = "ogr2ogr -t_srs 'EPSG:4326' -f GeoJSON " . $output . " " . $input;
            shell_exec($command);

            $layer->geom = preg_replace("[\n|\r|\n\r]", "", file_get_contents($output));
            $layer->file_name = $file_name;
            $layer->save();

            DB::commit();
            $this->setToggleModal();
            $this->dispatchBrowserEvent('toast', [
                'type' => 'success',
                'message' => __('SHP uploaded successfully.')
            ]);
            $this->emit("reload_shp", $layer->geom);
        } catch (Exception $ex) {
            DB::rollback();
            Log::error($ex);

            $this->dispatchBrowserEvent('toast', [
                'type' => 'error',
                'message' => __('Error trying to upload files.')
            ]);
        }
    }
}
