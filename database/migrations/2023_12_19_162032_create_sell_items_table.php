<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateSellItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('sell_items');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Schema::create('sell_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('sell_id')->nullable();
            $table->uuid('uuid')->default(DB::raw('(UUID())'))->unique();
            $table->foreign("sell_id")->references('uuid')->on('sells')->nullOnDelete();

            $table->dateTime('date_time')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('address_id')->nullable();
            
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign("admin_id")->references('id')->on('admin_users')->nullOnDelete();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->foreign('business_id')->references('id')->on('businesses')->nullOnDelete();

            $table->unsignedBigInteger('menu_id')->nullable();
            $table->integer('qty')->nullable();
            $table->integer('discount_amt')->nullable();
            $table->integer('total_amt')->nullable();
            $table->integer('gst_amt')->nullable();
           
            $table->foreign("user_id")->references('id')->on('users')->nullOnDelete();
            $table->foreign("address_id")->references('id')->on('addresses')->nullOnDelete();
            $table->foreign("menu_id")->references('id')->on('menus')->nullOnDelete();
            $table->boolean('order_status_preparing')->default(0)->nullable();
            $table->boolean('order_status_prepared')->default(0)->nullable();

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
        Schema::dropIfExists('sell_items');
    }
}
