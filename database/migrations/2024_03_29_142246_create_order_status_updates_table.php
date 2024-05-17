<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderStatusUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_status_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sell_id')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('order_status')->nullable();
            $table->string('delivery_status')->nullable();
            $table->string('status')->nullable();
            $table->string('dominant')->nullable();
            $table->foreign("user_id")->references('id')->on('users')->nullOnDelete();
            $table->foreign("driver_id")->references('id')->on('users')->nullOnDelete();
            $table->foreign("sell_id")->references('id')->on('sells')->nullOnDelete();

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
        Schema::dropIfExists('order_status_updates');
    }
}
