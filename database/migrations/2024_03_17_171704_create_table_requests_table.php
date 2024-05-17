<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('guests')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->integer('charge')->nullable();
            $table->string('status')->nullable();
           
            $table->unsignedBigInteger('dining_table_id')->nullable();
            $table->foreign("dining_table_id")->references('id')->on('dining_tables')->nullOnDelete();
            $table->string('payment_status')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('order_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign("admin_id")->references('id')->on('admin_users')->nullOnDelete();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->foreign('business_id')->references('id')->on('businesses')->nullOnDelete();

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
        Schema::dropIfExists('table_requests');
    }
}
