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
        Schema::table('finding_types', function (Blueprint $table) {
            $table->integer('price')->nullable()->after('name');
            $table->string('currency')->nullable()->after('price');
            $table->unsignedBigInteger('parent_finding_type_id')->nullable()->after('currency');
            $table->foreign('parent_finding_type_id')->references('id')->on('finding_types');
            $table->unsignedBigInteger("parent_user_id")->nullable()->after("parent_finding_type_id");
            $table->foreign("parent_user_id")->references("id")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('finding_types', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropForeign(['parent_finding_type_id']);
            $table->dropColumn('parent_finding_type_id');
            $table->dropForeign(['parent_user_id']);
            $table->dropColumn('parent_user_id');
        });
    }
};
