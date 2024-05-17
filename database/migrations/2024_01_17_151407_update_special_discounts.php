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
        Schema::table('special_discounts', function (Blueprint $table) {
            $table->unsignedBigInteger('sell_id')->nullable();
            $table->foreign("sell_id")->references('id')->on('sells')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('special_discounts', function (Blueprint $table) {
            //
        });
    }
};
