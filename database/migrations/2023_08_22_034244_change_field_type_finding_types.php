<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // change id field type to increments in finding_types table
        Schema::table('finding_types', function (Blueprint $table) {
            // drop sequence if exists
            DB::statement('DROP SEQUENCE IF EXISTS finding_types_id_seq CASCADE');
            $table->increments('id')->change();
            $table->dropUnique('finding_types_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
            // change id field type to integer in finding_types table
            Schema::table('finding_types', function (Blueprint $table) {
                $table->integer('id')->change();
                $table->unique('name');
            });
    }
};
