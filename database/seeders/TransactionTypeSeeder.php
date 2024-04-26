<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
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
                'id' => TransactionType::IN,
                'transaction_type' => 'in'
            ],
            [
                'id' => TransactionType::OUT,
                'transaction_type' => 'out'
            ],
        ];

        foreach ($items as $item)
            if (is_null(TransactionType::find($item['id']))) TransactionType::create($item);
    }
}
