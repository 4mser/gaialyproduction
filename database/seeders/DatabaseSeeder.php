<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AiModelsSeeder::class);
        $this->call(PalletesSeeder::class);
        $this->call(BillingPlansSeeder::class);
        $this->call(TransactionStatusSeeder::class);
        $this->call(TransactionTypeSeeder::class);
        $this->call(ProfilesSeeder::class);
        $this->call(CompaniesSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(LayerTypesSeeder::class);
        $this->call(OperationTypesSeeder::class);
        $this->call(TestDataSeeder::class);
        $this->call(FindingTypeSeeder::class);
        $this->call(CurrencySeeder::class);
    }
}
