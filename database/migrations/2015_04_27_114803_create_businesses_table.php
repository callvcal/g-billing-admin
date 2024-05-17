<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('on_board_way')->nullable();
            $table->boolean('active')->nullable()->default(1);
            $table->string('plan')->nullable();
            $table->dateTime('on_board_date')->nullable();
            $table->dateTime('purchase_date')->nullable();
            $table->dateTime('last_subscription_date')->nullable();
            $table->dateTime('expiry_date')->nullable();
            
            $table->boolean('deleted')->default(0);
            $table->dateTime('deleting_date')->nullable();
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
        Schema::dropIfExists('businesses');
    }
};
