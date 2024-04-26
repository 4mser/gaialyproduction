<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::where('name', 'Test Company')->first();
        $items = [
            [
                'name' => 'Super',
                'last_name' => 'Administrator',
                'rut' => '12345678-0',
                'phone' => '56922222222',
                'email' => 'admin@admin.cl',
                'password' => bcrypt('123456789'),
                'email_verified_at' => Carbon::now(),
                'company_id' => $company->id,
                'profile_id' => Profile::SUPER_ADMIN,
            ],
            [
                'name' => 'Jhon',
                'last_name' => 'Doe',
                'rut' => '12345678-9',
                'phone' => '56933333333',
                'email' => 'jhon@doe.com',
                'password' => bcrypt('secret'),
                'email_verified_at' => Carbon::now(),
                'company_id' => $company->id,
                'profile_id' => Profile::SUPER_ADMIN,
            ]
        ];

        foreach ($items as $item) {
            if (User::where('email', $item['email'])->first() == null) {
                $user = User::create($item);
                $user->parent_user_id = $user->id;
                $user->save();
            }
        }
    }
}
