<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecialDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_discounts', function (Blueprint $table) {
            $table->id();
            $table->string('date_of_allocation')->nullable();
            $table->unsignedBigInteger('refering_user_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('is_used')->nullable();
            $table->boolean('is_valid')->nullable();
            $table->integer('discount')->nullable();
            $table->dateTime('used_date')->nullable();
            $table->string('mobile')->comment('used to check coupons validity')->nullable();
            $table->string('discount_index')->comment('1,2,3')->nullable();
          
            $table->foreign("user_id")->references('id')->on('users')->nullOnDelete();
            $table->foreign("refering_user_id")->references('id')->on('users')->nullOnDelete();
           
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
        Schema::dropIfExists('special_discounts');
    }
}
