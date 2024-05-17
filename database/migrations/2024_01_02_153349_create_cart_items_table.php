<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();;
            $table->dateTime('date_time')->nullable();
            $table->integer('qty')->nullable();
            $table->integer('total_amt')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('menu_id')->nullable();
            $table->foreign("user_id")->references('id')->on('users')->nullOnDelete();
            $table->foreign("menu_id")->references('id')->on('menus')->nullOnDelete();
            $table->unsignedBigInteger('dining_table_id')->nullable();
            $table->foreign('dining_table_id')->references('id')->on('dining_tables')->nullOnDelete();
       
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
