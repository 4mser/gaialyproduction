<?php

namespace Database\Seeders;

use App\Models\Layer;
use App\Models\LayerType;
use Illuminate\Database\Seeder;

class ThermalDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $layers = Layer::where('layer_type_id', LayerType::THERMO)->whereNull('thermal_data')->get();
        foreach ($layers as $layer) {
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
}
