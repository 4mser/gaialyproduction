<?php

namespace Database\Seeders;

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CompaniesSeeder extends Seeder
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
                'name' => 'Test Company',
                'parent_user_id' => 1,
            ],
        ];

        foreach ($items as $item) {
            if (Company::where('name', $item['name'])->first() == null) {
                $company = Company::create($item);
                $company->save();
            }
        }
    }
}
