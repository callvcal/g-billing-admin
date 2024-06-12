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
        Schema::table('sell_items', function (Blueprint $table) {
            $table->double('qty')->nullable()->change();
            $table->double('total_amt')->nullable()->change();
        });
        Schema::table('sells', function (Blueprint $table) {
            $table->double('total_amt')->nullable()->change();
            $table->double('extra_charge_value')->nullable();
            $table->double('discount_value')->nullable();
            $table->string('discount_type')->nullable();
            $table->string('extra_charge_type')->nullable();
        });
        Schema::table('cart_items', function (Blueprint $table) {
            $table->double('qty')->nullable()->change();
            $table->double('total_amt')->nullable()->change();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
