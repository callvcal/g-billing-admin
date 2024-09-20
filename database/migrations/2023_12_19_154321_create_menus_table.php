<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('code')->nullable();
            $table->string('description')->nullable();
            $table->integer('price')->nullable();
            $table->integer('price_din_in')->nullable();
            $table->integer('price_take_away')->nullable();
            $table->integer('price_with_delivery')->nullable();
            $table->boolean('in_stock')->default(1)->nullable();
            $table->integer('discount')->comment('In %')->nullable();
            $table->string('food_type')->comment('veg,non-veg')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('kitchen_id')->nullable();
            $table->integer('ratings')->nullable();
            $table->integer('sells')->nullable();
            $table->foreign("category_id")->references('id')->on('categories')->nullOnDelete();
            $table->foreign("subcategory_id")->references('id')->on('sub_categories')->nullOnDelete();
            $table->foreign("unit_id")->references('id')->on('units')->nullOnDelete();
            $table->foreign("kitchen_id")->references('id')->on('kitchens')->nullOnDelete();
            $table->unsignedBigInteger('admin_id')->nullable();
            
            
            $table->foreign("admin_id")->references('id')->on('admin_users')->nullOnDelete();
            $table->boolean('active')->default(1)->nullable();
            $table->string('stock_status')->nullable();
            $table->string('calories_count')->nullable();
            $table->string('weight_per_serving')->nullable();
            $table->string('proteins_count')->nullable();
            $table->integer('qty')->nullable();
            $table->integer('stocks')->default(0)->nullable();
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
        Schema::dropIfExists('menus');
    }
}
