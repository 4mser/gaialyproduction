<?php

namespace Database\Seeders;

use App\Models\TransactionStatus;
use Illuminate\Database\Seeder;

class TransactionStatusSeeder extends Seeder
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
                'id' => TransactionStatus::PENDING,
                'transaction_status' => 'pending'
            ],
            [
                'id' => TransactionStatus::SUCCESS,
                'transaction_status' => 'success'
            ],
            [
                'id' => TransactionStatus::FAILED,
                'transaction_status' => 'failed'
            ],
        ];

        foreach ($items as $item)
            if (is_null(TransactionStatus::find($item['id']))) TransactionStatus::create($item);
    }
}
