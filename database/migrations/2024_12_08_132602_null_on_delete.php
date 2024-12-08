<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();

            $table->dropForeign(['credit_wallet_id']);
            $table->foreign('credit_wallet_id')->references('id')->on('wallets')->nullOnDelete();

            $table->dropForeign(['debit_wallet_id']);
            $table->foreign('debit_wallet_id')->references('id')->on('wallets')->nullOnDelete();

            $table->dropForeign(['reward_point_id']);
            $table->foreign('reward_point_id')->references('id')->on('rewards')->nullOnDelete();
        });

        Schema::table('referals', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('reward_transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();

            $table->dropForeign(['referal_id']);
            $table->foreign('referal_id')->references('id')->on('referals')->nullOnDelete();

            $table->dropForeign(['reward_id']);
            $table->foreign('reward_id')->references('id')->on('rewards')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add reverse logic here if necessary
    }
};
