<?php

namespace Database\Seeders;

use App\Models\OperationType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OperationTypesSeeder extends Seeder
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
                'id' => OperationType::LINEAS_ELECTRICAS,
                'name' => 'Power Lines',
            ],
            [
                'id' => OperationType::SOLAR_PANELS,
                'name' => 'Solar Panels',
            ],
            [
                'id' => OperationType::WIND_TURBINES,
                'name' => 'Wind Turbines',
            ],
            [
                'id' => OperationType::CIVIL_WORKS,
                'name' => 'Civil Works',
            ]
        ];

        foreach ($items as $item) {
            if (OperationType::where('name', $item['name'])->first() == null) {
                OperationType::create($item);
            }
        }
    }
}
