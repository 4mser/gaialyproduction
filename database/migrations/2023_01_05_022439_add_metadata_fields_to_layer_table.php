<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('layers', function (Blueprint $table) {
            $table->double("metadata_lat")->nullable();
            $table->double("metadata_lng")->nullable();
            $table->string("metadata_date")->nullable();
            $table->string("metadata_original_name")->nullable();
            $table->string("metadata_model")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('layers', function (Blueprint $table) {
            $table->dropColumn("metadata_lat");
            $table->dropColumn("metadata_lng");
            $table->dropColumn("metadata_date");
            $table->dropColumn("metadata_original_name");
            $table->dropColumn("metadata_model");
        });
    }
};
