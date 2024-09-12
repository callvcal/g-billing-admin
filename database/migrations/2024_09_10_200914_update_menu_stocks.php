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
        Schema::table('menu_stocks', function (Blueprint $table) {
            $table->unsignedBigInteger('sell_item_id')->nullable();
            $table->foreign('sell_item_id')->references('id')->on('sell_items')->nullOnDelete();
            $table->unsignedBigInteger('sell_id')->nullable();
            $table->foreign('sell_id')->references('id')->on('sells')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_stocks', function (Blueprint $table) {
            //
        });
    }
};
