<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (DB::table('profiles')->where('id', 4)->doesntExist()) {
            DB::table('profiles')->insert([
                'id' => 4,
                'name' => 'Auditor',
                'description' => 'User with auditing privileges'
            ]);
        }
    }

    public function down()
    {
        DB::table('profiles')->where('id', 4)->delete();
    }
};

