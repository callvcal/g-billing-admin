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
