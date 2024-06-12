<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateSellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Schema::dropIfExists('-');
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Schema::create('sells', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->default(DB::raw('(UUID())'))->unique();

            $table->dateTime('date_time')->nullable();
            $table->string('order_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_method')->comment('cash,online')->nullable();
            $table->integer('total_amt')->nullable();
            $table->integer('delivery_charge')->nullable();
            $table->double('paid_amt')->nullable();
            $table->integer('gst_amt')->nullable();
            $table->string('gst_type')->nullable();
            $table->integer('discount_amt')->nullable();
            $table->integer('due_amt')->nullable();
            $table->string('user_type')->comment('new,subscriber')->nullable();
            $table->integer('items_count')->nullable();
            $table->string('sell_type')->comment('app,web')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->string('order_status')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('delivery_status')->nullable();
            $table->string('serve_type')->comment("counter,din-in,delivery,take-away")->nullable();
            $table->string('remark')->nullable();
            $table->integer('delivery_tip')->nullable();
            $table->string('delivery_instruction')->nullable();
            $table->string('cooking_notes')->nullable();
            $table->string('order_complete_otp')->nullable();
            $table->string('delivery_pick_up_otp')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->foreign("driver_id")->references('id')->on('users')->nullOnDelete();
            $table->foreign("user_id")->references('id')->on('users')->nullOnDelete();
            $table->foreign("coupon_id")->references('id')->on('coupons')->nullOnDelete();

            $table->unsignedBigInteger('special_discount_id')->nullable();
            $table->foreign("special_discount_id")->references('id')->on('special_discounts')->nullOnDelete();
            $table->string('customer_name')->nullable();
            $table->string('customer_mobile')->nullable();
            $table->unsignedBigInteger('dining_table_id')->nullable();
            $table->foreign('dining_table_id')->references('id')->on('dining_tables')->nullOnDelete();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->foreign('business_id')->references('id')->on('businesses')->nullOnDelete();
            $table->foreign("admin_id")->references('id')->on('admin_users')->nullOnDelete();
            $table->string('token_number')->nullable();
            $table->string('pos_status')->nullable();
            $table->text('full_address')->nullable();
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
        Schema::dropIfExists('sells');
    }
}
