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
        Schema::create('layer_data', function (Blueprint $table) {
            $table->id();
            $table->text('value')->nullable();
            $table->integer('layer_id')->unsigned();
            $table->foreign('layer_id')->references('id')->on('layers');
            $table->integer('layer_data_type_id');
            $table->foreign('layer_data_type_id')->references('id')->on('layer_data_types');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('layer_data');
    }
};
