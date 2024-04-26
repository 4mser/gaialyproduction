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
        Schema::create('layers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('geom'); // Geo JSON
            $table->text('symbology')->nullable(); // JSON
            $table->text('file_name')->nullable();
            $table->integer('file_size')->nullable();
            $table->string('file_extension', 10)->nullable();
            $table->string('hallazgo', 50)->nullable();
            $table->integer('layer_type_id')->unsigned();
            $table->foreign('layer_type_id')->references('id')->on('layer_types');
            $table->integer('operation_id')->unsigned();
            $table->foreign('operation_id')->references('id')->on('operations');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('layers');
    }
};
