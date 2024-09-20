<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiningTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dining_tables', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('charge')->nullable();
            $table->integer('capacity')->nullable();
            $table->string('number')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            
            
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->foreign("admin_id")->references('id')->on('admin_users')->nullOnDelete();
            $table->foreign("staff_id")->references('id')->on('admin_users')->nullOnDelete();
            $table->text('customer_name')->nullable();
            $table->text('customer_mobile')->nullable();
            $table->string('status')->nullable();
            $table->string('invoice_id')->nullable();
            $table->dateTime('date_time')->nullable();
            $table->integer('amount')->nullable();
            
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
        Schema::dropIfExists('dining_tables');
    }
}
