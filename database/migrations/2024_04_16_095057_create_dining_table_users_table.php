<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiningTableUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dining_table_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dining_table_id')->nullable();
            $table->foreign('dining_table_id')->references('id')->on('dining_tables')->nullOnDelete();
            $table->dateTime('date_time')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_mobile')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->unsignedBigInteger('dining_table_request_id')->nullable();
            $table->foreign('dining_table_request_id')->references('id')->on('table_requests')->nullOnDelete();
            $table->integer('amount')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign("admin_id")->references('id')->on('admin_users')->nullOnDelete();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->foreign('business_id')->references('id')->on('businesses')->nullOnDelete();
            $table->integer('discount')->nullable();
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
        Schema::dropIfExists('dining_table_users');
    }
}
