<?php

namespace App\Http\Livewire\Maps\Tools;

use App\Models\Layer;
use App\Models\LayerType;
use App\Models\Operation;
use App\Models\AiModel;
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

class UploadImageZip extends Component
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
        return view('livewire.maps.tools.upload-image-zip');
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

            if (auth()->user()->hasUnlimitedBalance() === false) {
                if (($required_credits > 0 && $required_credits  > auth()->user()->getCreditBalance()) || auth()->user()->getCreditBalance() < 0) {
                    $message = __('You do not have enough credits to perform this operation.');
                    throw new Exception($message, Response::HTTP_UNPROCESSABLE_ENTITY);
                }
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
                // Renombrar imagen
                $name = str_replace('.' . $file->getExtension(), '', $file->getBasename());
                $filename = $file->getBasename();
                $newRealPathName = $file->getPath() . '/' . Str::uuid()->toString() . '.' . $file->getExtension();
                File::move($file->getRealPath(), $newRealPathName);
                $file = new SplFileInfo($newRealPathName);
                // Renombrar imagen

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
                $totalModels = 0;
                foreach ($this->models as $model) {
                    if ($model["status"]) {
                        if (auth()->user()->hasUnlimitedBalance() === false) {
                            if ($model["price"] > auth()->user()->getCreditBalance()) {
                                $message = __('You do not have enough credits to perform this operation.');
                                throw new Exception($message, Response::HTTP_UNPROCESSABLE_ENTITY);
                            }
                        }
                        $totalModels++;
                        $layer = Layer::create([
                            'name' => $name,
                            'operation_id' => $this->operationId,
                            'geom' => $geom,
                            'layer_type_id' => LayerType::IMAGE,
                            'user_id' => auth()->user()->id,
                            'metadata_lat' => $lat,
                            'metadata_lng' => $long,
                            'metadata_date' => $date,
                            'metadata_original_name' => $filename,
                            'metadata_model' => $model["code"]
                        ]);
                        $res = copy(
                            $file->getRealPath(),
                            Storage::disk('uploads')->path('layers') . '/' . $layer->id . '.' . $file->getExtension()
                        );
                        $layer->file_name = 'layers/' . $layer->id . '.' . $file->getExtension();
                        $layer->file_size = $file->getSize();
                        $layer->file_extension = $file->getExtension();
                        $fileName = uploads_path('layers/' . $layer->id . '.' . $file->getExtension());
                        $clientOriginalName = $filename;

                        $hallazgos = $this->ApiService($fileName, $model["code"]);
                        if ($hallazgos['status']) {
                            // Create transaction
                            $transaction = null;
                            if (auth()->user()->hasUnlimitedBalance() === false) {
                                $transaction = Transaction::out([
                                    'user' => auth()->user()->parentUser,
                                    'credit' => $model['price'],
                                    'statusId' => TransactionStatus::PENDING,
                                    'description' => 'Processing image ' . $clientOriginalName . ' with ' . $model['name'] . ' AI Model',
                                ]);
                            }
                            // Update transaction (success)
                            list($width, $height) = getimagesize($fileName);
                            $layer->width = $width;
                            $layer->height = $height;
                            $detections = [];
                            if (isset($hallazgos["data"])) {
                                foreach ($hallazgos['data'] as $key => $item) {
                                    $h = $height * $item["h"];
                                    $w = $width * $item["w"];

                                    $x = $width * $item["x"];
                                    $y = $height - ($height * $item["y"]);

                                    $wmax = round($x + $w / 2);
                                    $wmin = round($x - $w / 2);
                                    $hmax = round($y - $h / 2);
                                    $hmin = round($y + $h / 2);

                                    $detection['geom'] = json_encode([
                                        'type' => 'Feature',
                                        'properties' => [],
                                        'geometry' => [
                                            'type' => 'Polygon',
                                            'coordinates' => [
                                                [
                                                    [$wmin, $hmin],
                                                    [$wmax, $hmin],
                                                    [$wmax, $hmax],
                                                    [$wmin, $hmax],
                                                    [$wmin, $hmin],
                                                ]
                                            ]
                                        ]
                                    ]);
                                    $detection['label'] = $item["label"];
                                    $detection['confidence'] = round($item["confidence"] * 100, 2);
                                    $detection["severity"] = 6;

                                    array_push($detections, $detection);
                                }

                                $layer->data = json_encode($detections);

                                //creating preview
                                $severityColors = [
                                    1 => '#f44336',
                                    2 => '#f49236',
                                    3 => '#ffeb3b',
                                    4 => '#34D399',
                                    5 => '#047857',
                                    6 => '#673ab7',
                                ];
                                $image = imagecreatefromjpeg(public_path('storage/' . $layer->file_name));
                                $image = imagescale($image, $layer->width, $layer->height);
                                $font = public_path('fonts/Roboto-Regular.ttf');
                                $imageData["data"] = json_decode($layer->data);
                                if (is_array($imageData["data"])) {
                                    foreach ($imageData["data"] as $key => $item) {
                                        $detection['id'] = $key;
                                        $detection['severity'] = $item->severity;
                                        $detection['label'] = $item->label;
                                        $detection['geom'] = $item->geom ? json_decode($item->geom) : null;
                                        $detection['geom'] = $detection['geom']?->geometry?->coordinates[0];
                                        $text =  $detection['label'];
                                        if ($detection['geom']) {
                                            $x = $detection['geom'][0][0];
                                            $y = $layer->height - $detection['geom'][0][1];
                                            $textHeight = $layer->width < 1000 ? 20 : $layer->width * 0.01;
                                            $textDim = imagettfbbox($textHeight, 0, $font, $text);
                                            $fontBackgroundColor = imagecolorallocate($image, 255, 255, 255);
                                            $hex = ltrim($severityColors[$item->severity], '#');
                                            if ($hex == "000000") {
                                                $hex = "101010";
                                            }
                                            $fontBackgroundColor = imagecolorallocate($image, hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2)));
                                            $fontColor   = imagecolorallocate($image, 255, 255, 255);
                                            imagefilledrectangle($image, $x, $y - ($textHeight * 1.5), $x + $textDim[2] + ($textHeight / 2), $y, $fontBackgroundColor);
                                            imagettftext($image, $textHeight, 0, $x + 5, $y - ($textHeight / 2) + 5, $fontColor, $font, $text);
                                            $polygonArray = [];
                                            foreach ($detection['geom'] as $key => $point) {
                                                $polygonArray[] = $point[0];
                                                $polygonArray[] = $layer->height - $point[1];
                                            }
                                            imagesetthickness($image, 5);
                                            imagepolygon($image, $polygonArray, count($detection['geom']), $fontBackgroundColor);
                                        }
                                    }
                                }

                                // save image
                                imagejpeg($image, public_path("storage/layers/preview_{$layer->id}." . $layer->file_extension));
                                imagedestroy($image);
                            }
                            $layer->save();
                            if ($transaction) {
                                $transaction->description = 'Image ' . $clientOriginalName . ' processed with ' . $model['name'] . ' AI Model by ' . auth()->user()->name . ' ' . auth()->user()->last_name;
                                $transaction->transaction_status_id = TransactionStatus::SUCCESS;
                                $transaction->save();
                            }
                        } else {
                            throw new \Exception('Error processing image with ' . $model['name'] . ' AI Model', Response::HTTP_UNPROCESSABLE_ENTITY);
                            Log::error($hallazgos);
                        }
                    }
                }
                if ($totalModels == 0) {
                    $layer = Layer::create([
                        'name' => $name,
                        'operation_id' => $this->operationId,
                        'geom' => $geom,
                        'layer_type_id' => LayerType::IMAGE,
                        'user_id' => auth()->user()->id,
                        'metadata_lat' => $lat,
                        'metadata_lng' => $long,
                        'metadata_date' => $date,
                        'metadata_original_name' => $filename,
                    ]);
                    $layer->file_extension = strtolower($file->getExtension());
                    copy(
                        $file->getRealPath(),
                        Storage::disk('uploads')->path('layers') . '/' . $layer->id . '.' . $layer->file_extension
                    );
                    $layer->file_name = 'layers/' . $layer->id . '.' . $layer->file_extension;
                    $layer->file_size = $file->getSize();

                    $fileName = uploads_path('layers/' . $layer->id . '.' . $layer->file_extension);
                    list($width, $height) = getimagesize($fileName);
                    $layer->width = $width;
                    $layer->height = $height;
                    $layer->data = json_encode([]);
                    $image = imagecreatefromjpeg(public_path('storage/' . $layer->file_name));
                    $image = imagescale($image, $layer->width, $layer->height);
                    imagejpeg($image, public_path("storage/layers/preview_{$layer->id}." . $layer->file_extension));
                    imagedestroy($image);
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
