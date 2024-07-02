<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePremiumPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('premium_plans', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->nullable();
            $table->string('name')->nullable();
            $table->string('key')->nullable();
            $table->string('android_key')->nullable();
            $table->string('ios_key')->nullable();
            $table->json('features')->nullable();
            $table->string('description')->nullable();
            $table->integer('days')->nullable();
            $table->integer('months')->nullable();
            $table->integer('charge')->nullable();
            $table->integer('years')->nullable();
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
        Schema::dropIfExists('premium_plans');
    }
}
