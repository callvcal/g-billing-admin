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
        Schema::create('app_home_menu', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('app_home_id')->nullable();
            $table->unsignedBigInteger('menu_id')->nullable();
            $table->foreign("app_home_id")->references('id')->on('app_home')->nullOnDelete();
            $table->foreign("menu_id")->references('id')->on('menus')->nullOnDelete();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign("admin_id")->references('id')->on('admin_users')->nullOnDelete();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->foreign('business_id')->references('id')->on('businesses')->nullOnDelete();

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
        Schema::dropIfExists('app_home_music_artist');
    }
};