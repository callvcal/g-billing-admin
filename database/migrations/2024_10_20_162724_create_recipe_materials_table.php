<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipeMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipe_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recipe_id')->index()->nullable();
            $table->unsignedBigInteger('menu_id')->index()->nullable();
            $table->unsignedBigInteger('admin_id')->index()->nullable();
            $table->unsignedBigInteger('business_id')->index()->nullable();
            $table->unsignedBigInteger('material_id')->index()->nullable();
            $table->double('qty')->default('0.0')->nullable();
            $table->boolean('allow_dine_in')->nullable();
            $table->boolean('allow_parcel_delivery')->nullable();
            $table->foreign("menu_id")->references('id')->on('menus')->nullOnDelete();
            $table->foreign("recipe_id")->references('id')->on('recipes')->nullOnDelete();
            $table->foreign("material_id")->references('id')->on('materials')->nullOnDelete();
            $table->foreign("business_id")->references('id')->on('businesses')->nullOnDelete();
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
        Schema::dropIfExists('recipe_materials');
    }
}
