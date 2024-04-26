<?php

namespace App\Http\Livewire\Images;

use App\Models\Layer;
use App\Models\LayerType;
use App\Models\FindingType;
use App\Models\Currency;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $Image = null;

    public $imageId = null;

    public $showLabels = false;

    public $selectedDetectionId = null;

    public $imageData = [];

    public $label = null;

    public $severity = null;

    public $solved = null;

    public $remedy = null;

    public $cost = null;

    public $currency = null;

    public $category = null;

    public $temp_min = null;

    public $temp_max = null;

    public $temp_avg = null;

    public $geom = null;

    public $confidence = null;

    public $thermoData = "{}";

    public $findingTypeOptions  = [];

    public $currencies = [];

    public $severityColors = [
        1 => '#f44336',
        2 => '#f49236',
        3 => '#ffeb3b',
        4 => '#34D399',
        5 => '#047857',
        6 => '#673ab7',
    ];

    public $tempMin = null;

    public $tempMax = null;

    public $tempAvg = null;

    public function mount($id)
    {
        $this->imageId = $id;
        $query = "WITH RECURSIVE FindingTypeCTE AS (
            SELECT 
                id, 
                name, 
                parent_finding_type_id, 
                0 AS nivel,
                ARRAY[id] AS ruta
            FROM finding_types
            WHERE parent_finding_type_id IS NULL AND parent_user_id = " . auth()->user()->getParentID() . "
            UNION ALL
            SELECT 
                ft.id, 
                ft.name, 
                ft.parent_finding_type_id, 
                cte.nivel + 1,
                cte.ruta || ft.id
            FROM finding_types ft
            INNER JOIN FindingTypeCTE cte ON cte.id = ft.parent_finding_type_id
        )
        SELECT 
            ft.id,
            CASE
                WHEN ft.nivel > 0 THEN
                    RPAD('', ft.nivel * 2, '-') || ft.name
                ELSE
                    ft.name
            END AS name,
            f.price,
            f.currency
        FROM FindingTypeCTE ft
        LEFT JOIN finding_types f ON ft.id = f.id
        ORDER BY ft.ruta";

        $this->findingTypeOptions = DB::select(DB::raw($query));
        $this->currencies = Currency::get();
        $this->setData();
    }

    public function render()
    {
        return view('livewire.images.form')->layout('layouts.image');
    }

    public function goBack()
    {
        return redirect('/map');
    }

    public function hideDetails()
    {
        $this->selectedDetectionId = null;
    }

    public function showDetails($id)
    {
        if ($this->selectedDetectionId === "new") {
            return;
        }
        $this->selectedDetectionId = $id;
        $this->setData();
    }

    public function removeDetails($id)
    {
        $detections = json_decode($this->Image->data);
        array_splice($detections, $id, 1);
        $this->Image->data = json_encode($detections);
        $this->Image->save();
        $this->createPreviewImage($detections);
        // reload page
        return redirect()->to('/map/image/' . $this->imageId);
    }

    public function setSeverity($severity)
    {
        $this->severity = $severity;
    }

    public function setSolved()
    {
        $this->solved = !$this->solved;
    }

    public function saveForm()
    {
        // TODO: Guardar todos los campos
        $detections = json_decode($this->Image->data) ?? [];
        if ($this->selectedDetectionId === "new") {
            $this->selectedDetectionId = count($detections);
            $detections[$this->selectedDetectionId] = new \stdClass();
            $aux = $this->geom;
            $this->geom["features"] = [];
            $this->geom["features"][0] = $aux;
        }

        if ($this->Image->layer_type_id === LayerType::THERMO) {
            $positions = $this->geom["features"][0]["geometry"]["coordinates"][0];

            $x_coords = array_column($positions, 0);
            $y_coords = array_column($positions, 1);

            $left = floor(min($x_coords));
            $right = floor(max($x_coords));
            $top = 512 - floor(min($y_coords));
            $bottom = 512 - floor(max($y_coords));
            $jdata = json_decode($this->thermoData);
            $result = array();
            for ($i = $left; $i <= $right; $i++) {
                for ($j = $bottom; $j <= $top; $j++) {
                    $result[] = $jdata[$j][$i];
                }
            }
            $this->temp_min = min($result);
            $this->temp_max = max($result);
            $sum = array_sum($result);
            $this->temp_avg = number_format($sum / count($result), 1);
        }

        if($this->confidence > 100 ) {
            $this->confidence = 100;
        }

        if($this->confidence < 0 ) {
            $this->confidence = 0;
        }
        $detections[$this->selectedDetectionId]->label = $this->label;
        $detections[$this->selectedDetectionId]->severity = $this->severity ?? 6;
        $detections[$this->selectedDetectionId]->solved = $this->solved;
        $detections[$this->selectedDetectionId]->remedy = $this->remedy;
        $detections[$this->selectedDetectionId]->cost = $this->cost;
        $detections[$this->selectedDetectionId]->currency = $this->currency;
        $detections[$this->selectedDetectionId]->category = $this->category;
        $detections[$this->selectedDetectionId]->confidence = $this->confidence;
        $detections[$this->selectedDetectionId]->temp_min = $this->temp_min;
        $detections[$this->selectedDetectionId]->temp_max = $this->temp_max;
        $detections[$this->selectedDetectionId]->temp_avg = $this->temp_avg;
        $detections[$this->selectedDetectionId]->geom = json_encode($this->geom["features"][0]);
        $this->Image->data = json_encode($detections);
        $this->Image->save();
        $this->createPreviewImage($detections);
        // reload page
        return redirect()->to('/map/image/' . $this->imageId);
    }

    public function setData()
    {

        $this->Image = Layer::findOrFail($this->imageId);
        $this->imageData["width"] = $this->Image->width;
        $this->imageData["height"] = $this->Image->height;
        $this->imageData["data"] = json_decode($this->Image->data);
        $this->imageData["metadata_lat"] = $this->Image->metadata_lat;
        $this->imageData["metadata_lng"] = $this->Image->metadata_lng;
        $this->imageData["metadata_date"] = $this->Image->metadata_date;
        $this->imageData["metadata_original_name"] = $this->Image->metadata_original_name;
        $this->imageData["metadata_model"] = $this->Image->metadata_model;

        $detections = [];
        if (is_array($this->imageData["data"])) {
            foreach ($this->imageData["data"] as $key => $item) {
                $detection['id'] = $key;
                $detection['severity'] = $item->severity;
                $detection['label'] = $item->label;
                $detection['confidence'] = $item->confidence ?? null;
                $detection['geom'] = $item->geom;
                $detection['simbology'] = json_encode([
                    'fillColor' => $this->severityColors[$item->severity],
                    'color' => $this->severityColors[$item->severity],
                    'fillOpacity' => 0,
                ]);
                $detection['temp_min'] = $item->temp_min ?? null;
                $detection['temp_max'] = $item->temp_max ?? null;
                $detection['temp_avg'] = $item->temp_avg ?? null;
                if ($key === $this->selectedDetectionId) {
                    $this->label = $item->label;
                    $this->severity = $detection["severity"];
                    $this->solved = $item->solved ?? false;
                    $this->remedy = $item->remedy ?? null;
                    $this->cost = $item->cost ?? null;
                    $this->currency = $item->currency ?? 'USD';
                    $this->category = $item->category ?? null;
                    $this->temp_min = $item->temp_min ?? null;
                    $this->temp_max = $item->temp_max ?? null;
                    $this->temp_avg = $item->temp_avg ?? null;
                    $this->confidence = $item->confidence ?? null;
                    // emit editLayer
                    $this->emit('editLayer', $detection);
                }

                array_push($detections, $detection);
            }
        }
        if ($this->selectedDetectionId === "new") {
            $this->label = null;
            $this->severity = null;
            $this->solved = false;
            $this->remedy = null;
            $this->cost = null;
            $this->currency = 'USD';
            $this->category = null;
            $this->temp_min = null;
            $this->temp_max = null;
            $this->temp_avg = null;
            $this->confidence = null;
            $this->geom = null;
            $this->emit('drawLayer');
        }

        $this->imageData["detections"] = $detections;

        if ($this->Image->layer_type_id === LayerType::THERMO) {
            $this->tempMin = $this->Image->thermal_data['min_temp'];
            $this->tempMax = $this->Image->thermal_data['max_temp'];
            $this->tempAvg = $this->Image->thermal_data['avg_temp'];
            $this->thermoData = json_encode(get_temperatures_from_layers($this->Image->id));
        }
    }

    public function createPreviewImage($detections)
    {
        // duplicate image with same size
        $image = imagecreatefromjpeg(public_path('storage/' . $this->Image->file_name));
        $image = imagescale($image, $this->Image->width, $this->Image->height);
        $font = public_path('fonts/Roboto-Regular.ttf');
        if (is_array($detections)) {
            foreach ($detections as $key => $item) {
                $detection['id'] = $key;
                $detection['severity'] = $item->severity;
                $detection['label'] = $item->label;
                $detection['geom'] = $item->geom ? json_decode($item->geom) : null;
                $detection['geom'] = $detection['geom']?->geometry?->coordinates[0];
                $text =  $detection['label'];
                if ($detection['geom']) {
                    $x = $detection['geom'][0][0];
                    $y = $this->Image->height - $detection['geom'][0][1];
                    $textHeight = $this->Image->width < 1000 ? 20 : $this->Image->width * 0.01;
                    $textDim = imagettfbbox($textHeight, 0, $font, $text);
                    $fontBackgroundColor = imagecolorallocate($image, 255, 255, 255);
                    $hex = ltrim($this->severityColors[$item->severity], '#');
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
                        $polygonArray[] = $this->Image->height - $point[1];
                    }
                    imagesetthickness($image, 5);
                    imagepolygon($image, $polygonArray, count($detection['geom']), $fontBackgroundColor);
                }
            }
        }

        // save image
        imagejpeg($image, public_path("storage/layers/preview_{$this->Image->id}." . $this->Image->file_extension));
        imagedestroy($image);
    }
}
