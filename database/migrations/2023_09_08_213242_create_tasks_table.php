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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->enum('status', ['pending', 'running', 'completed', 'failed'])->default('pending');
            $table->string('uuid')->unique()->nullable();
            $table->unsignedInteger('attempts')->default(0);
            $table->unsignedInteger('channel')->default(1);
            $table->unsignedInteger('percent_complete')->default(0);
            $table->string('name');
            $table->json('data')->nullable();
            $table->text('exception')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('layer_id')->nullable();
            $table->foreign('layer_id')->references('id')->on('layers');
            $table->enum('type', ['tiles', 'webodm'])->default('tiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
