<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfilesSeeder extends Seeder
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
                'id' => Profile::SUPER_ADMIN,
                'name' => 'Super Admin',
                'description' => 'System Super Admin'
            ],
            [
                'id' => Profile::OWNER,
                'name' => 'Owner',
                'description' => 'Account Owner'
            ],
            [
                'id' => Profile::USER,
                'name' => 'User',
                'description' => 'Regular User'
            ],
            [
                'id' => Profile::AUDITOR,
                'name' => 'Auditor',
                'description' => 'Auditor'
            ],
        ];

        foreach ($items as $item) {
            if (Profile::find($item['id']) == null) {
                Profile::create($item);
            }
        }
    }
}
