<?php

namespace Database\Seeders;

use App\Models\Operation;
use App\Models\OperationType;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'admin@admin.cl')->first();

        $operation = [
            'name' => 'Test Operation',
            'company_id' => $user->company_id,
            'description' => 'This section is for testing',
            'operation_type_id' => OperationType::LINEAS_ELECTRICAS,
        ];

        $operation = Operation::firstOrNew($operation);
        $operation->save();

        $operation->users()->sync([$user->id]);
    }
}
