<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('credit_wallet_id')->nullable();
            $table->unsignedBigInteger('debit_wallet_id')->nullable();
            $table->unsignedBigInteger('reward_point_id')->nullable();
            $table->enum("transaction_type",['credit','debit'])->nullable();
            $table->integer("authenticated_user_id")->nullable();
            $table->enum("authenticated_user_role",['user','admin'])->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('credit_wallet_id')->references('id')->on('wallets');
            $table->foreign('debit_wallet_id')->references('id')->on('wallets');
            $table->foreign('reward_point_id')->references('id')->on('rewards');
            $table->double("amount")->default(0)->nullable();
            $table->string("name")->nullable();
            $table->string("mobile")->nullable();
            $table->string("status")->nullable();
            $table->string("order_id")->nullable();
            $table->string("transaction_id")->nullable();
            $table->string("txn_token")->nullable();
            $table->dateTime("date_time")->nullable();
            $table->text("remark")->nullable();
          
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
        Schema::dropIfExists('wallet_transactions');
    }
}
