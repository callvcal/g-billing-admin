<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRewardTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reward_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('reward_id')->nullable();
            $table->unsignedBigInteger('referal_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('referal_id')->references('id')->on('referals');
            $table->foreign('reward_id')->references('id')->on('rewards');
            $table->integer("points")->default(0)->nullable();
            $table->string('mobile')->nullable();
            $table->string('name')->nullable();
            $table->dateTime("date_time")->nullable();
            $table->enum("transaction_type",['credit','debit'])->nullable();

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
        Schema::dropIfExists('reward_transactions');
    }
}
