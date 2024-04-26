<?php

namespace App\Http\Livewire\Maps\Tools;

use App\Models\AiModel;
use App\Models\Layer;
use App\Models\LayerType;
use App\Models\Pallete;
use CURLFile;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadThermalImage extends Component
{
    use WithFileUploads;

    public $name = null;
    public $file = null;
    public $iteration = 0;
    public $toggleModal = 'hidden';
    public $operationId = null;
    public $hasCoordinates = true;
    public $pallete;
    public $palletes = [];
    public $lat = null;
    public $lng = null;
    public $models = [];
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
        return view('livewire.maps.tools.upload-thermal-image', compact('cameraModelsSupported'));
    }

    public function setToggleModal()
    {
        $this->name = null;
        $this->file = null;
        $this->iteration++;
        $this->lat = null;
        $this->lng = null;
        $this->hasCoordinates = true;
        $this->models = [];
        $this->toggleModal = ($this->toggleModal == 'hidden') ? '' : 'hidden';
    }

    public function upload()
    {
        $this->validate(
            [
                'name' => 'required',
                'file' => 'required|file|mimes:jpg|max:51200'
            ],
            [
                'required' => __('This field is required.'),
                'file.mimes' => __('The file must be on JPG or JPEG Format.')
            ]
        );
        DB::beginTransaction();
        try {
            $required_credits = array_reduce($this->models, function ($carry, $item) {
                if ($item['status'] === true) {
                    return $carry + $item['price'];
                }
                return $carry;
            }, 0);
            // if (auth()->user()->hasUnlimitedBalance() === false) {
            //     if (($required_credits > 0 && $required_credits > auth()->user()->getCreditBalance()) || auth()->user()->getCreditBalance() < 0) {
            //         $message = __('You do not have enough credits to perform this operation.');
            //         throw new Exception($message, Response::HTTP_UNPROCESSABLE_ENTITY);
            //     }
            // }

            // Check DJI camera model support
            $output = shell_exec("exiftool -Model {$this->file->getRealPath()}");
            if (!is_null($output)) {
                $output = explode(PHP_EOL, trim($output));
                $cameraModel = trim(explode(':', $output[0])[1]);
                if (!dji_has_support($cameraModel))
                    throw new Exception(__('The camera model "' . $cameraModel . '" is not supported.'), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            // Check DJI camera model support

            $this->hasCoordinates = true;

            // Validar coordenadas
            $message = __('No GPS coordinates were found on the image.');
            if ($this->lat && $this->lng) {
                $message = __('The GPS coordinates are not valid.');
                $lat = floatval($this->lat);
                $long = floatval($this->lng);
                $date = date('Y-m-d H:i:s');
                if ($lat < -90 || $lat > 90 || $long < -180 || $long > 180) {
                    $this->hasCoordinates = false;
                    throw new Exception($message, Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            } else {
                $gps_data = shell_exec("exiftool {$this->file->getRealPath()} -c '%+.6f'  -GPSLatitude -GPSLongitude -createdate ");
                if (is_null($gps_data)) {
                    $this->hasCoordinates = false;
                    throw new Exception($message, Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                $gps_data = explode(PHP_EOL, trim($gps_data));
                if (count($gps_data) < 2) {
                    $this->hasCoordinates = false;
                    throw new Exception($message, Response::HTTP_UNPROCESSABLE_ENTITY);
                }
                if (!$this->hasCoordinates) {
                    throw new Exception($message, Response::HTTP_UNPROCESSABLE_ENTITY);
                }
                $lat = trim(explode(':', $gps_data[0])[1]);
                $long = trim(explode(':', $gps_data[1])[1]);
                $date = $gps_data[2] ? trim(explode('-', trim(explode(': ', $gps_data[2])[1]))[0]) : date('Y-m-d H:i:s');
            }
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
            $command = 'python3 ' . base_path('sdk/TSDK/dji_sdk.py') . ' ' . $this->file->getRealPath() . ' /tmp/' . $tmpJson . ' 0 ' . $libPath;
            $result = shell_exec($command);
            // Extract json

            // New image
            // Generate .raw file
            $tmpRawImage = $uniqid . '.raw';
            $tmpImage = $uniqid . '.jpg';
            $command = 'export LD_LIBRARY_PATH=' . base_path('sdk/TSDK') . '; ' . base_path('sdk/TSDK/dji_irp') . ' -s ' . $this->file->getRealPath() . ' -a process -o /tmp/' . $tmpRawImage . ' -p ' . $this->pallete;
            Log::info('Ejecución del comando ' . $command);
            $result = shell_exec($command);
            Log::info('Resultado del comando ' . $result);
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
                    'name' => $this->name,
                    'operation_id' => $this->operationId,
                    'geom' => $geom,
                    'layer_type_id' => LayerType::THERMO,
                    'user_id' => auth()->user()->id,
                    'metadata_lat' => $lat,
                    'metadata_lng' => $long,
                    'metadata_date' => $date,
                    'metadata_original_name' => $this->file->getClientOriginalName(),
                    'file_extension' => strtolower(pathinfo('/tmp/' . $tmpImage, PATHINFO_EXTENSION)),
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

            DB::commit();
            $this->setToggleModal();
            if ($totalModels == 0) {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'success',
                    'message' => __("The image was loaded succesfully")
                ]);
            } else {
                if ($layer->data) {
                    $hallazgos = json_decode($layer->data);
                    $hallazgos = count($hallazgos);
                    $this->dispatchBrowserEvent('alert', [
                        'type' => 'info',
                        'message' => __("{$hallazgos} findings were found in the image.")
                    ]);
                } else {
                    $this->dispatchBrowserEvent('alert', [
                        'type' => 'info',
                        'message' => __("No findings")
                    ]);
                }
            }

            // $this->emit("show_hallazgo", $layer->hallazgo);
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
                    'message' => __('Error trying to load image.')
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
