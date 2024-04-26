<?php

namespace Database\Seeders;

use App\Models\FindingType;
use Illuminate\Database\Seeder;

class FindingTypeSeeder extends Seeder
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
                'id' => FindingType::CRACK,
                'name' => 'Crack',
            ],
            [
                'id' => FindingType::DEFECT_FAILURE,
                'name' => 'Defect/Failure',
            ],
            [
                'id' => FindingType::DIRT,
                'name' => 'Dirt',
            ],
            [
                'id' => FindingType::CORROSION_WEAR,
                'name' => 'Corrosion/Wear',
            ],
            [
                'id' => FindingType::UNIDENTIFIED_OBJECT,
                'name' => 'Unidentified Object',
            ],
            [
                'id' => FindingType::DANGEROUS_OBJECT,
                'name' => 'Dangerous Object',
            ],
            [
                'id' => FindingType::OTHER,
                'name' => 'Other',
            ]
        ];

        foreach ($items as $item) {
            if (FindingType::where('name', $item['name'])->first() == null) {
                FindingType::create($item);
            }
        }
    }
}
