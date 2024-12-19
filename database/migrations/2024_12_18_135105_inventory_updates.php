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
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('allow_dine_in')->default(0)->nullable();
            $table->boolean('allow_take_away')->default(0)->nullable();
            $table->boolean('allow_delivery')->default(0)->nullable();
        });
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->boolean('allow_dine_in')->default(0)->nullable();
            $table->boolean('allow_take_away')->default(0)->nullable();
            $table->boolean('allow_delivery')->default(0)->nullable();
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
