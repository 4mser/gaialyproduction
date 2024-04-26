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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string("last_name")->nullable();
            $table->string("rut")->nullable();
            $table->string("phone")->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger("parent_user_id")->nullable();
            $table->foreign("parent_user_id")->references("id")->on("users");
            $table->unsignedBigInteger("company_id")->nullable();
            $table->foreign("company_id")->references("id")->on("companies");
            $table->unsignedBigInteger("profile_id")->nullable();
            $table->foreign("profile_id")->references("id")->on("profiles");
            $table->string("title")->nullable();
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->string('signature_photo_path', 2048)->nullable();
            $table->string('company_photo_path', 2048)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('free_trial_expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
