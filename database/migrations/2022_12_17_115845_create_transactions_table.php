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
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid();
            $table->foreignId('user_id');
            $table->foreignId('transaction_type_id');
            $table->foreignId('transaction_status_id');
            $table->text('description');
            $table->text('data')->nullable();
            $table->integer('before_credit_balance');
            $table->integer('current_credit_balance');
            $table->integer('credit');
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
        Schema::dropIfExists('transactions');
    }
};
