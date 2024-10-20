<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id')->index()->nullable();
            $table->unsignedBigInteger('category_id')->index()->nullable();
            $table->unsignedBigInteger('subcategory_id')->index()->nullable();
            $table->unsignedBigInteger('admin_id')->index()->nullable();
            $table->unsignedBigInteger('business_id')->index()->nullable();
            $table->foreign("menu_id")->references('id')->on('menus')->nullOnDelete();
            $table->foreign("category_id")->references('id')->on('categories')->nullOnDelete();
            $table->foreign("subcategory_id")->references('id')->on('sub_categories')->nullOnDelete();
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
        Schema::dropIfExists('recipes');
    }
}
