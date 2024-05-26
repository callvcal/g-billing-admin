<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->json('json')->nullable();
            $table->dateTime('expiry_date')->nullable();
            $table->dateTime('date_time')->nullable();
            $table->string('transaction_status_local')->nullable();
            $table->string('transaction_status_callback')->nullable();
            $table->string('callback_json')->nullable();
            $table->string('plan')->nullable();
            $table->double('amount')->nullable();
            $table->double('gst')->nullable();
            $table->double('service_charge')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->foreign('business_id')->references('id')->on('businesses')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('admin_users')->nullOnDelete();

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
        Schema::dropIfExists('payment_transactions');
    }
}
