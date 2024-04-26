<?php

namespace App\Http\Livewire\Maps\Tools;

use App\Models\Layer;
use App\Models\LayerType;
use App\Models\Operation;
use App\Models\AiModel;
use App\Models\Pallete;
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
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class UploadThermalImageZip extends Component
{

    use WithFileUploads;

    public $name = null;
    public $file = null;
    public $iteration = 0;
    public $toggleModal = 'hidden';
    public $operationId = null;
    public $models = [];
    public $pallete;
    public $palletes = [];


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
        $this->palletes = Pallete::getPalletes();
        $this->pallete = 'iron_red';
    }

    public function render()
    {
        $cameraModelsSupported = dji_camera_models_supported();
        return view('livewire.maps.tools.upload-thermal-image-zip', compact('cameraModelsSupported'));
    }

    public function setToggleModal()
    {
        $this->name = null;
        $this->file = null;
        $this->iteration++;
        $this->models = [];
        $this->toggleModal = ($this->toggleModal == 'hidden') ? '' : 'hidden';
    }

    public function upload()
    {
        $this->validate(
            [
                'file' => 'required|file|mimes:zip|max:100000'
            ],
            [
                'required' => __('This field is required.'),
                'file.mimes' => __('The file must be on ZIP Format.')
            ]
        );
        DB::beginTransaction();
        try {
            $uuid = Str::uuid()->toString();
            $pathTo = storage_path('app/livewire-tmp/zip/' . $uuid);

            $zip = new ZipArchive();
            if ($zip->open($this->file->getRealPath())) {
                $zip->extractTo($pathTo);
            }
            $items = [];

            $files = File::allFiles($pathTo);
            if (count($files) > env('MAX_IMAGES_PER_ZIP', 20)) {
                $message = __('The maximum number of images per zip is ' . env('MAX_IMAGES_PER_ZIP', 1000) . ' and the zip has ' . count($files) . '.');
                throw new Exception($message, Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $required_credits = array_reduce($this->models, function ($carry, $item) {
                if ($item['status'] === true) {
                    return $carry + $item['price'];
                }
                return $carry;
            }, 0);

            $required_credits = $required_credits * count($files);

            // if (auth()->user()->hasUnlimitedBalance() === false) {
            //     if (($required_credits > 0 && $required_credits  > auth()->user()->getCreditBalance()) || auth()->user()->getCreditBalance() < 0) {
            //         $message = __('You do not have enough credits to perform this operation.');
            //         throw new Exception($message, Response::HTTP_UNPROCESSABLE_ENTITY);
            //     }
            // }

            // VALIDAR QUE TODAS LAS IMAGENES TENGAN COORDENADAS Y QUE SEAN JPG O JPEG
            foreach ($files as $file) {
                // Check DJI camera model support
                $output = shell_exec("exiftool -Model {$file->getRealPath()}");
                if (!is_null($output)) {
                    $output = explode(PHP_EOL, trim($output));
                    $cameraModel = trim(explode(':', $output[0])[1]);
                    if (!dji_has_support($cameraModel))
                        throw new Exception(__('The camera model "' . $cameraModel . '" is not supported.'), Response::HTTP_UNPROCESSABLE_ENTITY);
                }
                // Check DJI camera model support
            }

            // VALIDAR QUE TODAS LAS IMAGENES TENGAN COORDENADAS Y QUE SEAN JPG O JPEG
            foreach ($files as $file) {
                if (!in_array(strtolower($file->getExtension()), ['jpg', 'jpeg'])) {
                    $message = __('The file ' . $file->getFilename() . ' is not a JPG or JPEG file.');
                    throw new Exception($message, Response::HTTP_UNPROCESSABLE_ENTITY);
                }
                $message = __('The file ' . $file->getFilename() . ' does not have coordinates.');
                $gps_data = shell_exec("exiftool {$file->getRealPath()} -c '%+.6f'  -GPSLatitude -GPSLongitude");
                if (is_null($gps_data)) {
                    throw new Exception($message, Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                $gps_data = explode(PHP_EOL, trim($gps_data));
                if (count($gps_data) < 2) {
                    throw new Exception($message, Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }
            foreach ($files as $file) {

                $name = str_replace('.' . $file->getExtension(), '', $file->getBasename());
                $filename = $file->getBasename();

                $gps_data = shell_exec("exiftool {$file->getRealPath()} -c '%+.6f'  -GPSLatitude -GPSLongitude -createdate ");
                $gps_data = explode(PHP_EOL, trim($gps_data));
                $lat = trim(explode(':', $gps_data[0])[1]);
                $long = trim(explode(':', $gps_data[1])[1]);
                $date = $gps_data[2] ? trim(explode('-', trim(explode(': ', $gps_data[2])[1]))[0]) : date('Y-m-d H:i:s');
                $geom = json_encode([
                    'type' => 'Point',
                    'coordinates' => [
                        floatval($long),
                        floatval($lat)
                    ]
                ]);

                $uniqid = uniqid();

                // Extract json
                $libPath = base_path('sdk/TSDK');
                $tmpJson = $uniqid . '.json';
                $command = 'python3 ' . base_path('sdk/TSDK/dji_sdk.py') . ' ' . $file->getRealPath() . ' /tmp/' . $tmpJson . ' 0 ' . $libPath;
                $result = shell_exec($command);
                // Extract json

                // New image
                // Generate .raw file
                $tmpRawImage = $uniqid . '.raw';
                $tmpImage = $uniqid . '.jpg';
                $command = 'export LD_LIBRARY_PATH=' . base_path('sdk/TSDK') . '; ' . base_path('sdk/TSDK/dji_irp') . ' -s ' . $file->getRealPath() . ' -a process -o /tmp/' . $tmpRawImage . ' -p ' . $this->pallete;
                Log::info('Ejecución del comando ' . $command);
                $result = shell_exec($command);
                if (!str_contains($result, 'Save image file as')) {
                    $message = __('The thermal image could not be processed.');
                    throw new Exception($message, Response::HTTP_UNPROCESSABLE_ENTITY);
                }
                // Generate .raw file

                // Convert the .raw file to .jpg
                $width = 640;
                $height = 512;

                $rawImage = file_get_contents('/tmp/' . $tmpRawImage);
                $image = imagecreatetruecolor($width, $height);

                $index = 0;
                for ($y = 0; $y < $height; $y++) {
                    for ($x = 0; $x < $width; $x++) {
                        $r = ord($rawImage[$index++]);
                        $g = ord($rawImage[$index++]);
                        $b = ord($rawImage[$index++]);
                        $color = imagecolorallocate($image, $r, $g, $b);
                        imagesetpixel($image, $x, $y, $color);
                    }
                }
                imagejpeg($image, '/tmp/' . $tmpImage);
                // Convert the .raw file to .jpg
                // New image

                $totalModels = 0;
                if ($totalModels == 0) {
                    $layer = Layer::create([
                        'name' => $name,
                        'operation_id' => $this->operationId,
                        'geom' => $geom,
                        'layer_type_id' => LayerType::THERMO,
                        'user_id' => auth()->user()->id,
                        'metadata_lat' => $lat,
                        'metadata_lng' => $long,
                        'metadata_date' => $date,
                        'metadata_original_name' => $filename,
                        'file_extension' => strtolower($file->getExtension()),
                        'file_size' => filesize('/tmp/' . $tmpImage),
                    ]);
                    $fileName = 'layers/' . $layer->id . '.' . $layer->file_extension;
                    $layer->file_name = $fileName;
                    copy('/tmp/' . $tmpImage, uploads_path($layer->file_name));
                    copy('/tmp/' . $tmpJson, uploads_path('layers/' . $layer->id . '.json'));
                    list($width, $height) = getimagesize(uploads_path($layer->file_name));
                    $layer->width = $width;
                    $layer->height = $height;
                    $layer->data = json_encode([]);

                    $image = imagecreatefromjpeg(public_path('storage/' . $layer->file_name));
                    $image = imagescale($image, $layer->width, $layer->height);
                    imagejpeg($image, public_path("storage/layers/preview_{$layer->id}." . $layer->file_extension));
                    imagedestroy($image);

                    $temperatures = get_temperatures_from_layers($layer->id);
                    $thermalData = [
                        'min_temp' => min_temp($temperatures),
                        'max_temp' => max_temp($temperatures),
                        'avg_temp' => avg_temp($temperatures),
                    ];
                    $layer->thermal_data = $thermalData;

                    $layer->save();
                }
            }
            DB::commit();
            $this->setToggleModal();

            $this->dispatchBrowserEvent('toast', [
                'message' => __('The images were successfully loaded.')
            ]);
            $this->emit("reload_img");
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
                    'message' => __('Error trying to load the images.')
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
        if (empty($response['data']) || empty($response['file']) || empty($response['url']))
            $response['status'] = false;
        curl_close($curl);
        return $response;
    }
}
