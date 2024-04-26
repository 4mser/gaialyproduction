<?php

namespace App\Http\Livewire\Maps\Tools;

use App\Mail\Task as MailTask;
use App\Models\Layer;
use App\Models\LayerType;
use App\Models\Operation;
use App\Models\Task;
use App\Rules\TifFile;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UploadTif extends Component
{

    use WithFileUploads;

    public $name = null;
    public $file = null;
    public $iteration = 0;
    public $toggleModal = 'hidden';
    public $operationId = null;

    public Operation $operation;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.maps.tools.upload-tif');
    }

    public function mount()
    {
        $this->operationId = session()->get('operationId');
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
        // TODO: Check file size (max 10gb)
        $this->validate(
            [
                'name' => 'required',
                'file' => ['required', 'file', new TifFile],
            ],
            [
                'required' => __('This field is required.'),
            ]
        );
        DB::beginTransaction();
        try {
            // if tiles folder does not exist, create it
            if (!Storage::disk('uploads')->exists('tiles')) {
                Storage::disk('uploads')->makeDirectory('tiles');
            }
            $this->dispatchBrowserEvent('showLoading');
            $layer = Layer::create([
                'name' => $this->name,
                'operation_id' => $this->operationId,
                'geom' => json_encode([]),
                'layer_type_id' => LayerType::ORTHOPHOTO,
                'user_id' => auth()->user()->id,
                'metadata_original_name' => $this->file->getClientOriginalName(),
                'visible' => false,
            ]);
            $layer->file_size = $this->file->getSize();
            $layer->file_extension = $this->file->getClientOriginalExtension();
            $layer->file_name = $this->file->storeAs('tiles', $layer->id . '.' . $layer->file_extension);
            $fileName = uploads_path('tiles/' . $layer->id . '.' . $layer->file_extension);
            list($width, $height) = getimagesize($fileName);
            $layer->width = $width;
            $layer->height = $height;


            // Validar subida de archivo
            $file = $this->file->getRealPath();
            $info = shell_exec("gdalinfo -json $file");
            $info = json_decode($info);
            // validate if $info->wgs84Extent exists
            if (!isset($info->wgs84Extent)) {
                $message = __('The file does not have a valid projection');
                throw new Exception($message, Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $geom = $info->wgs84Extent->coordinates[0];
            $layer->geom = json_encode($geom);
            $layer->save();
            DB::commit();
            $this->setToggleModal();
            $this->dispatchBrowserEvent('toast', [
                'type' => 'success',
                'message' => __('TIF File uploaded succesfully.')
            ]);

            // Insert tail task
            $task = Task::create([
                'status' => Task::STATUS_PENDING,
                'uuid' => Str::uuid(),
                'name' => $layer->name,
                'user_id' => auth()->user()->id,
                'layer_id' => $layer->id,
                'type' => Task::TYPE_TILES,
            ]);
            // Send email to user

            $mailData = [
                'subject' => __('Task created'),
                'task' => $task,
            ];
            Mail::to($task->user->email)->send(new MailTask($mailData));

            $this->emit("reload_tif", $layer->geom);
        } catch (Exception $ex) {
            DB::rollback();
            Log::error($ex);
            $this->dispatchBrowserEvent('toast', [
                'type' => 'error',
                'message' => __($ex->getMessage())
            ]);
        }
    }
}
