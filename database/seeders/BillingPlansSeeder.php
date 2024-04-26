<?php

namespace Database\Seeders;

use App\Models\BillingPlan;
use Illuminate\Database\Seeder;

class BillingPlansSeeder extends Seeder
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
                'position' => 1,
                'code' => 'premium',
                'title' => 'Premium',
                'subtitle' => fake()->sentence(),
                'description' => fake()->paragraphs(3, true),
                'credits' => 1000,
                'price' => 90
            ],
            [
                'position' => 2,
                'code' => 'enterprise',
                'title' => 'Enterprise',
                'subtitle' => fake()->sentence(),
                'description' => fake()->paragraphs(3, true),
                'credits' => 10000,
                'price' => 800
            ],

        ];

        foreach ($items as $item)
            if (is_null(BillingPlan::where('code', $item['code'])->first())) BillingPlan::create($item);
    }
}
