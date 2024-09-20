<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflineTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('amount')->nullable();
            $table->string('type')->nullable();
            $table->text('cause')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign("admin_id")->references('id')->on('admin_users')->nullOnDelete();
            
            

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
        Schema::dropIfExists('offline_transactions');
    }
}
