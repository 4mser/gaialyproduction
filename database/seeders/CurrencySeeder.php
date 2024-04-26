<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {

        $items = [
            ['code' => 'USD', 'name' => 'United States Dollar'],
            ['code' => 'EUR', 'name' => 'Euro'],
            ['code' => 'JPY', 'name' => 'Japanese yen'],
            ['code' => 'GBP', 'name' => 'Pound sterling'],
            ['code' => 'CHF', 'name' => 'Swiss franc'],
            ['code' => 'CAD', 'name' => 'Canadian dollar'],
            ['code' => 'AUD', 'name' => 'Australian dollar'],
            ['code' => 'NZD', 'name' => 'New Zealand dollar'],
            ['code' => 'CNY', 'name' => 'Chinese yuan'],
            ['code' => 'INR', 'name' => 'Indian rupee'],
            ['code' => 'BRL', 'name' => 'Brazilian real'],
            ['code' => 'MXN', 'name' => 'Mexican peso'],
            ['code' => 'ARS', 'name' => 'Argentine peso'],
            ['code' => 'COP', 'name' => 'Colombian peso'],
            ['code' => 'CLP', 'name' => 'Chilean peso'],
            ['code' => 'PEN', 'name' => 'Peruvian sol'],
        ];

        foreach ($items as $item) {
            if (Currency::where('code', $item['code'])->first() == null) {
                Currency::create($item);
            }
        }
    }
}
