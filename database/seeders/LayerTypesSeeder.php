<?php

namespace Database\Seeders;

use App\Models\Layer;
use App\Models\LayerType;
use Illuminate\Database\Seeder;

class LayerTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'id' => LayerType::SHAPEFILE,
                'name' => 'Shape',
            ],
            [
                'id' => LayerType::KML,
                'name' => 'KML',
            ],
            [
                'id' => LayerType::ORTHOPHOTO,
                'name' => 'Orthophoto',
            ],
            [
                'id' => LayerType::IMAGE,
                'name' => 'Image',
            ],
            [
                'id' => LayerType::DRAWN,
                'name' => 'Drawn',
            ],  
            [
                'id' => LayerType::THERMO,
                'name' => 'Thermo',
            ],
        ];

        foreach ($items as $item) {
            if (LayerType::where('name', $item['name'])->first() == null) {
                LayerType::create($item);
            }
        }
    }
}
