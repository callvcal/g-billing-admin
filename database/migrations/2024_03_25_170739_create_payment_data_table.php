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
        Schema::create('payment_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('sell_id')->nullable();
            $table->foreign("user_id")->references('id')->on('users')->nullOnDelete();
            $table->json('json')->nullable();
            $table->string('payment_gateway')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('request_type')->nullable();
            $table->string('receipt_id')->nullable();
            $table->foreign("sell_id")->references('id')->on('sells')->nullOnDelete();
            $table->unsignedBigInteger('wallet_transaction_id')->nullable();
            $table->unsignedBigInteger('wallet_id')->nullable();
            $table->foreign('wallet_id')->references('id')->on('wallets')->nullOnDelete();
            $table->foreign('wallet_transaction_id')->references('id')->on('wallet_transactions')->nullOnDelete();
            $table->unsignedBigInteger('table_request_id')->nullable();
            $table->foreign('table_request_id')->references('id')->on('table_requests')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_data');
    }
};
