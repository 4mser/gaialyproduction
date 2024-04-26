<?php

namespace Database\Seeders;

use App\Models\Palette;
use App\Models\Pallete;
use Illuminate\Database\Seeder;

class PalletesSeeder extends Seeder
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
                'name' => 'Arctic',
                'code' => 'arctic',
            ],
            [
                'name' => 'Black hot',
                'code' => 'black_hot',
            ],
            [
                'name' => 'Fulgurite',
                'code' => 'fulgurite',
            ],
            [
                'name' => 'Hot iron',
                'code' => 'hot_iron',
            ],
            [
                'name' => 'Iron red',
                'code' => 'iron_red',
            ],
            [
                'name' => 'Medical',
                'code' => 'medical',
            ],
            [
                'name' => 'Rainbow 1',
                'code' => 'rainbow1',
            ],
            [
                'name' => 'Rainbow 2',
                'code' => 'rainbow2',
            ],
            [
                'name' => 'Tint',
                'code' => 'tint',
            ],
            [
                'name' => 'White hot',
                'code' => 'white_hot',
            ],
        ];

        foreach ($items as $item)
            if (is_null(Pallete::where('code', $item['code'])->first())) Pallete::create($item);
    }
}
