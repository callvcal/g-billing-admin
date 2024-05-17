<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferalTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referal_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referring_user_id')->nullable();
            $table->unsignedBigInteger('referred_user_id')->nullable();
            $table->foreign('referring_user_id')->references('id')->on('users');
            $table->foreign('referred_user_id')->references('id')->on('users');
            $table->enum("transaction_type",['credit','debit'])->nullable();
            $table->text("remark")->nullable();
            $table->string('mobile')->nullable();
            $table->string('name')->nullable();
            $table->string('referance_code')->nullable();
            $table->dateTime("date_time")->nullable();

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
        Schema::dropIfExists('referal_transactions');
    }
}
