<?php

namespace App\Http\Livewire\Maps\Tools;

use App\Mail\newUser;
use App\Mail\Task as MailTask;
use App\Models\Layer;
use App\Models\LayerType;
use App\Models\Operation;
use App\Models\AiModel;
use App\Models\Task;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use SplFileInfo;
use CURLFile;
use App\Models\Transaction;
use App\Models\TransactionStatus;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class UploadImageZipToTif extends Component
{

    use WithFileUploads;

    public $name = null;
    public $file = null;
    public $iteration = 0;
    public $toggleModal = 'hidden';
    public $operationId = null;
    public $models = [];


    public Operation $operation;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount()
    {
        $this->operationId = session()->get('operationId');
        $this->models = AiModel::getModels();
    }

    public function render()
    {
        return view('livewire.maps.tools.upload-image-zip-to-tif');
    }

    public function setToggleModal()
    {
        $this->name = null;
        $this->file = null;
        $this->iteration++;
        $this->models = AiModel::getModels();
        $this->toggleModal = ($this->toggleModal == 'hidden') ? '' : 'hidden';
    }

    public function upload()
    {
        $this->validate(
            [
                'file' => 'required|file|mimes:zip'
            ],
            [
                'required' => __('This field is required.'),
                'file.mimes' => __('The file must be on ZIP Format.')
            ]
        );
        DB::beginTransaction();
        try {
            $zip = new ZipArchive();
            if ($zip->open($this->file->getRealPath())) {
                $zipTotalFiles = $zip->count();
                // Loop through the files in the ZIP archive
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    // Get the name of the file in the ZIP archive
                    $filename = $zip->getNameIndex($i);

                    // Get the MIME type of the file using finfo
                    $finfo = new \finfo(FILEINFO_MIME_TYPE);
                    $fileMimetype = $finfo->file('zip://' . $this->file->getRealPath() . '#' . $filename);

                    // Check if the file has a JPG or JPEG MIME type
                    if (!in_array($fileMimetype, ['image/jpeg', 'image/jpg'])) {
                        throw new Exception(__('The file "' . $this->file->getClientOriginalName() . '" must contains only JPG or JPEG files.'), Response::HTTP_UNPROCESSABLE_ENTITY);
                    }
                }

                $zipMaxFiles = config('services.webodm.max_zip_files');
                if ($zipTotalFiles > $zipMaxFiles) {
                    throw new Exception(__('The file ":file" has :total images, but must have a maximum of :max.', ['total' => $zipTotalFiles, 'file' => $this->file->getClientOriginalName(), 'max' => $zipMaxFiles]), Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                $layerName = $this->file->getClientOriginalName();
                $layerName = pathinfo($layerName, PATHINFO_FILENAME);

                $requiredCredits = config('services.webodm.credit');
                if (auth()->user()->hasUnlimitedBalance() === false) {
                    if (($requiredCredits > 0 && $requiredCredits  > auth()->user()->getCreditBalance()) || auth()->user()->getCreditBalance() < 0) {
                        $message = __('You do not have enough credits to perform this operation.');
                        throw new Exception($message, Response::HTTP_UNPROCESSABLE_ENTITY);
                    }

                    $transaction = Transaction::out([
                        'user' => auth()->user()->parentUser,
                        'credit' => $requiredCredits,
                        'statusId' => TransactionStatus::SUCCESS,
                        'description' => __('Layer :name', ['name' => $layerName]),
                    ]);
                }

                $layer = Layer::create([
                    'name' => $layerName,
                    'operation_id' => $this->operationId,
                    'geom' => '',
                    'layer_type_id' => LayerType::ORTHOPHOTO,
                    'user_id' => auth()->user()->id,
                    'visible' => false,
                ]);

                $task = Task::create([
                    'status' => Task::STATUS_PENDING,
                    'uuid' => Str::uuid(),
                    'name' => __('Layer :name', ['name' => $layerName]),
                    'user_id' => auth()->user()->id,
                    'layer_id' => $layer->id,
                    'type' => Task::TYPE_WEBODM,
                ]);

                $res = $zip->extractTo(uploads_path('tiles/' . $layer->id . '_images'));
                if (!$res) {
                    throw new Exception(__('Error trying to extract the images.'));
                }
                $zip->close();

                $this->file->storeAs('zip', $layer->id . '.zip');

                DB::commit();

                $this->setToggleModal();

                $this->dispatchBrowserEvent('toast', [
                    'message' => __('The file was uploaded successfully.')
                ]);
                $this->emit("reload_img");
            } else {
                throw new Exception(__('Error trying to open the zip file.'), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $mailData = [
                'subject' => __('Task created'),
                'task' => $task,
            ];
            Mail::to($task->user->email)->send(new MailTask($mailData));
        } catch (Exception $ex) {
            DB::rollback();
            Log::error($ex);
            if ($ex->getCode() == Response::HTTP_UNPROCESSABLE_ENTITY) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'warning',
                    'message' => $ex->getMessage()
                ]);
            } else {
                $this->dispatchBrowserEvent('toast', [
                    'type' => 'error',
                    'message' => __('Error trying to load the file.')
                ]);
            }
        }
    }

    public function setModel($id)
    {
        // found the model into the models array and set the status
        $this->models = collect($this->models)->map(function ($model) use ($id) {
            if ($model['id'] == $id) {
                $model['status'] = !$model['status'];
            }
            return $model;
        })->toArray();
    }

    private function ApiService($image, $service)
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => env('API_URL'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('image' => new CURLFile($image), 'service' => $service),
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    'Authorization: Bearer ' . env('API_TOKEN')
                ),
            ));

            $response = json_decode(curl_exec($curl), true);
            $response['status'] = true;
        } catch (Exception $e) {
            $response['status'] = false;
            Log::error($e);
        }
        curl_close($curl);
        return $response;
    }
}
