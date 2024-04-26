<?php

namespace Database\Seeders;

use App\Models\AiModel;
use App\Models\BillingPlan;
use Illuminate\Database\Seeder;

class AiModelsSeeder extends Seeder
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
                "code" => "inspection-all",
                "name" => "Inspection",
                "description" => "Electrical infrastructure damage detection",
                "price" => 1,
            ]
        ];

        foreach ($items as $item)
            if (is_null(AiModel::where('code', $item['code'])->first())) AiModel::create($item);
    }
}
