<?php

namespace Database\Seeders;

use App\Models\Layer;
use App\Models\LayerType;
use App\Models\Operation;
use App\Models\OperationType;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class DemoDataSeeder extends Seeder
{

    private $severityColors = Layer::SEVERITY_COLORS;
    
    private $inspections = [
        [
            'filename' => 'demo_01.jpg',
            'layer_type_id' => LayerType::THERMO,
            'lat' => -33.54589,
            'long' => -70.941816,
            'detections' => '[{"label":"Aisladores","severity":1,"solved":false,"remedy":"Aisladores continuos en buen estado, sin puntos calientes","cost":"0","currency":"USD","category":"7","confidence":"100","temp_min":11.2,"temp_max":56.7,"temp_avg":"38.2","geom":"{\"type\":\"Feature\",\"properties\":[],\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[151.474197,312.5],[232.958145,320.5],[275.949675,219.5],[201.464349,196.5],[151.474197,312.5]]]}}"},{"label":"N\/Aisladores","severity":1,"solved":false,"remedy":"Torre con aisladores s\u00f3lo en un costado","cost":"0","currency":"USD","category":"7","confidence":"100","temp_min":21.3,"temp_max":51.9,"temp_avg":"43.4","geom":"{\"type\":\"Feature\",\"properties\":[],\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[494.840654,417],[591.821548,411],[522.835139,262],[440.85129,275],[494.840654,417]]]}}"}]'
        ],
        [
            'filename' => 'demo_02.jpg',
            'layer_type_id' => LayerType::IMAGE,
            'lat' => -33.545903,
            'long' => -70.941792,
            'detections' => '[{"label":"Aisladores continuos","severity":1,"solved":false,"remedy":"No hay falta de aisladores, se ven en buen estado","cost":"0","currency":"USD","category":"7","confidence":"100","temp_min":null,"temp_max":null,"temp_avg":null,"geom":"{\"type\":\"Feature\",\"properties\":[],\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[2143.588734,2940],[3003.419342,2804],[2915.436675,512],[1943.628127,636],[2139.589521,3020],[2143.588734,2940]]]}}"}]'
        ],
        [
            'filename' => 'demo_03.jpg',
            'layer_type_id' => LayerType::IMAGE,
            'lat' => -23.126588,
            'long' => -70.303975,
            'detections' => '[{"label":"190.249 m3","severity":1,"solved":false,"remedy":"Dep\u00f3sito de ceniza","cost":"0","currency":"USD","category":"7","confidence":"100","temp_min":null,"temp_max":null,"temp_avg":null,"geom":"{\"type\":\"Feature\",\"properties\":[],\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[435.814063,896],[0,1400],[0,1716],[687.764428,1940],[1143.674611,2008],[1663.572188,2012],[1895.526492,2012],[2091.487887,1920],[2211.464251,1900],[2315.443766,1836],[2439.419342,1800],[2511.405161,1824],[2703.367343,1768],[2803.347646,1772],[2827.342919,1704],[2775.353161,1696],[2803.347646,1616],[2771.353949,1568],[2963.316132,1436],[3063.296435,1368],[3599.190861,1280],[4056,1248],[4056,1132],[3875.136498,1140],[3755.160134,1148],[3711.1688,1104],[3719.167225,912],[3711.1688,452],[3143.280678,0],[1327.638369,0],[1083.686429,116],[807.740792,352],[515.798306,724],[435.814063,896]]]}}"}]'
        ],
        [
            'filename' => 'demo_04.jpg',
            'layer_type_id' => LayerType::THERMO,
            'lat' => 33.567908,
            'long' => -70.90428,
            'detections' => '[{"label":"Panel\/buen estado","severity":1,"solved":false,"remedy":"Panel en buen estado para contraste","cost":"0","currency":"USD","category":"7","confidence":"100","temp_min":15.9,"temp_max":55.1,"temp_avg":"37.5","geom":"{\"type\":\"Feature\",\"properties\":[],\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[348.037227,155],[441.023439,150],[439.023439,86],[345.037227,94],[348.037227,155]]]}}"},{"label":"Puntos calientes","severity":4,"solved":false,"remedy":"Se identifican 3 puntos calientes, dos en cada esquina","cost":"120","currency":"USD","category":"2","confidence":"100","temp_min":21.6,"temp_max":60.7,"temp_avg":"39.4","geom":"{\"type\":\"Feature\",\"properties\":[],\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[176.903289,396],[259.886941,405],[259.886941,337],[173.90388,332],[176.903289,396]]]}}"}]'
        ],
        [
            'filename' => 'demo_05.jpg',
            'layer_type_id' => LayerType::IMAGE,
            'lat' => -33.567637,
            'long' => -70.904361,
            'detections' => '[{"label":"Suciedad","severity":3,"solved":false,"remedy":"Punto sucio","cost":"10","currency":"USD","category":"3","confidence":"100","temp_min":null,"temp_max":null,"temp_avg":null,"geom":"{\"type\":\"Feature\",\"properties\":[],\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[605.699429,574],[749.671066,586],[743.672247,420],[589.70258,414],[605.699429,574]]]}}"}]'
        ],
        [
            'filename' => 'demo_06.jpg',
            'layer_type_id' => LayerType::THERMO,
            'lat' => -33.56767,
            'long' => -70.904694,
            'detections' => '[{"label":"Puntos calientes","severity":4,"solved":false,"remedy":"Puntos calientes en esquinas","cost":"100","currency":"USD","category":"2","confidence":"100","temp_min":19.9,"temp_max":45.3,"temp_avg":"27.8","geom":"{\"type\":\"Feature\",\"properties\":[],\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[319.928107,325],[410.910183,325.5],[410.410282,267.5],[318.928304,269.5],[319.928107,325]]]}}"}]'
        ],
    ];

    private $user = null;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Obtener lista de archivos en carpeta examples
        DB::beginTransaction();
        try {
            $this->user = auth()->check() ? auth()->user() : \App\Models\User::orderBy('id')->first();
            if (!$this->user)
                throw new Exception('No user found');

            $operation = Operation::create([
                'name' => 'Demo - ' . time(),
                'description' => 'Demo inspection',
                'company_id' => $this->user->company_id,
                'operation_type_id' => array_rand(OperationType::getOptions()->toArray()),
            ]);
            $operation->users()->syncWithoutDetaching($this->user->id);

            for ($i = 0; $i < rand(5, 20); $i++) {
                $inspection = $this->inspections[array_rand($this->inspections)];
                if ($inspection['layer_type_id'] == LayerType::THERMO) {
                    $this->uploadThermo($operation, $inspection);
                } else {
                    // LayerType::IMAGE
                    $this->uploadImage($operation, $inspection);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
        }
    }

    public function getDemoFile($filenane)
    {
        $filePath = base_path('resources/demo/' . $filenane);
        return new UploadedFile($filePath, $filenane, 'image/jpeg', null, true);
    }

    public function uploadImage($operation, $inspection)
    {
        $demoFile = $this->getDemoFile($inspection['filename']);
        $long = $inspection['long'];
        $lat = $inspection['lat'];
        $date = date('Y-m-d H:i:s');

        // Check if image has coordinates
        $gpsData = shell_exec("exiftool {$demoFile->getPathname()} -c '%+.6f'  -GPSLatitude -GPSLongitude -createdate ");
        if (!is_null($gpsData)) {
            $gpsData = explode(PHP_EOL, trim($gpsData));
            if (count($gpsData) >= 2) {
                $lat = trim(explode(':', $gpsData[0])[1]);
                $long = trim(explode(':', $gpsData[1])[1]);
                $date = !empty($gpsData[2]) ? trim(explode('-', trim(explode(': ', $gpsData[2])[1]))[0]) : date('Y-m-d H:i:s');
            }
        }

        $faker = Faker\Factory::create();

        $name = trim($faker->text(10), '.');
        $geom = json_encode([
            'type' => 'Point',
            'coordinates' => [
                floatval($long),
                floatval($lat)
            ]
        ]);
        $originalName = $name . '.jpg';
        $layer = Layer::create([
            'name' => $name,
            'operation_id' => $operation->id,
            'geom' => $geom,
            'layer_type_id' => LayerType::IMAGE,
            'user_id' => $this->user->id,
            'metadata_lat' => $lat,
            'metadata_lng' => $long,
            'metadata_date' => $date,
            'metadata_original_name' => $originalName,
        ]);


        list($width, $height) = getimagesize($demoFile->getPathname());
        $layer->width = $width;
        $layer->height = $height;
        $layer->file_size = $demoFile->getSize();
        $layer->file_extension = $demoFile->getExtension();
        $layer->file_name = 'layers/' . $layer->id . '.' . $layer->file_extension;
        $layer->data = $inspection['detections'];
        $layer->save();

        File::copy($demoFile->getPathname(), uploads_path($layer->file_name));
        $img = imagecreatefromjpeg(uploads_path($layer->file_name));

        // PREVIEW
        $img = imagescale($img, $layer->width, $layer->height);
        imagejpeg($img, uploads_path("layers/preview_{$layer->id}." . $layer->file_extension));
        imagedestroy($img);
        // PREVIEW

        $this->createPreviewImageWithDetenctions($layer->id, json_decode($layer->data, true));
    }

    public function uploadThermo($operation, $inspection)
    {
        $demoFile = $this->getDemoFile($inspection['filename']);

        $long = $inspection['long'];
        $lat = $inspection['lat'];
        $date = date('Y-m-d H:i:s');

        // Check if image has coordinates
        $gpsData = shell_exec("exiftool {$demoFile->getPathname()} -c '%+.6f'  -GPSLatitude -GPSLongitude -createdate ");
        if (!is_null($gpsData)) {
            $gpsData = explode(PHP_EOL, trim($gpsData));
            if (count($gpsData) >= 2) {
                $lat = trim(explode(':', $gpsData[0])[1]);
                $long = trim(explode(':', $gpsData[1])[1]);
                $date = !empty($gpsData[2]) ? trim(explode('-', trim(explode(': ', $gpsData[2])[1]))[0]) : date('Y-m-d H:i:s');
            }
        }

        $faker = Faker\Factory::create();
        $name = trim($faker->text(10), '.');
        $geom = json_encode([
            'type' => 'Point',
            'coordinates' => [
                floatval($long),
                floatval($lat)
            ]
        ]);

        $originalName = $name . '.jpg';
        $layer = Layer::create([
            'name' => $name,
            'operation_id' => $operation->id,
            'geom' => $geom,
            'layer_type_id' => LayerType::THERMO,
            'user_id' => $this->user->id,
            'metadata_lat' => $lat,
            'metadata_lng' => $long,
            'metadata_date' => $date,
            'metadata_original_name' => $originalName,
        ]);

        list($width, $height) = getimagesize($demoFile->getPathname());
        $layer->width = $width;
        $layer->height = $height;
        $layer->file_size = $demoFile->getSize();
        $layer->file_extension = $demoFile->getExtension();
        $layer->file_name = 'layers/' . $layer->id . '.' . $layer->file_extension;
        $layer->data = $inspection['detections'];

        File::copy(base_path('resources/demo/' . pathinfo($demoFile->getClientOriginalName(), PATHINFO_FILENAME) . '.json'), uploads_path('layers/' . $layer->id . '.json'));

        $temperatures = get_temperatures_from_layers($layer->id);
        $thermalData = [
            'min_temp' => min_temp($temperatures),
            'max_temp' => max_temp($temperatures),
            'avg_temp' => avg_temp($temperatures),
        ];
        $layer->thermal_data = $thermalData;
        $layer->save();

        File::copy($demoFile->getPathname(), uploads_path($layer->file_name));
        $img = imagecreatefromjpeg(uploads_path($layer->file_name));

        // PREVIEW
        $img = imagescale($img, $layer->width, $layer->height);
        imagejpeg($img, uploads_path("layers/preview_{$layer->id}." . $layer->file_extension));
        imagedestroy($img);
        // PREVIEW

        $this->createPreviewImageWithDetenctions($layer->id, json_decode($layer->data, true));
    }

    public function createPreviewImageWithDetenctions($layerId, $detections)
    {
        $layer = Layer::findOrFail($layerId);
        // duplicate image with same size
        $image = imagecreatefromjpeg(uploads_path($layer->file_name));
        $image = imagescale($image, $layer->width, $layer->height);
        $font = public_path('fonts/Roboto-Regular.ttf');
        if (is_array($detections)) {
            foreach ($detections as $key => $item) {
                $item['geom'] = json_decode($item['geom']);
                $item['geom'] = $item['geom']?->geometry?->coordinates[0];
                if ($item['geom']) {
                    $x = $item['geom'][0][0];
                    $y = $layer->height - $item['geom'][0][1];
                    $textHeight = $layer->width < 1000 ? 20 : $layer->width * 0.01;
                    $textDim = imagettfbbox($textHeight, 0, $font, $item['label']);
                    $fontBackgroundColor = imagecolorallocate($image, 255, 255, 255);
                    $hex = ltrim($this->severityColors[$item['severity']], '#');
                    if ($hex == "000000") {
                        $hex = "101010";
                    }
                    $fontBackgroundColor = imagecolorallocate($image, hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2)));
                    $fontColor   = imagecolorallocate($image, 255, 255, 255);
                    imagefilledrectangle($image, $x, $y - ($textHeight * 1.5), $x + $textDim[2] + ($textHeight / 2), $y, $fontBackgroundColor);
                    imagettftext($image, $textHeight, 0, $x + 5, $y - ($textHeight / 2) + 5, $fontColor, $font, $item['label']);
                    $polygonArray = [];
                    foreach ($item['geom'] as $key => $point) {
                        $polygonArray[] = $point[0];
                        $polygonArray[] = $layer->height - $point[1];
                    }
                    imagesetthickness($image, 5);
                    imagepolygon($image, $polygonArray, count($item['geom']), $fontBackgroundColor);
                }
            }
        }

        // save image
        imagejpeg($image, uploads_path("layers/preview_{$layer->id}." . $layer->file_extension));
        imagedestroy($image);
    }
}
