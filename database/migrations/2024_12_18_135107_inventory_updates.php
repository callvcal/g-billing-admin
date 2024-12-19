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
            $table->dropColumn('allow_dine_in')->default(1)->nullable();
            $table->dropColumn('allow_take_away')->default(1)->nullable();
            $table->dropColumn('allow_delivery')->default(1)->nullable();
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('allow_dine_in')->default(1)->nullable();
            $table->boolean('allow_take_away')->default(1)->nullable();
            $table->boolean('allow_delivery')->default(1)->nullable();
        });
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->dropColumn('allow_dine_in')->default(1)->nullable();
            $table->dropColumn('allow_take_away')->default(1)->nullable();
            $table->dropColumn('allow_delivery')->default(1)->nullable();
        });
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->boolean('allow_dine_in')->default(1)->nullable();
            $table->boolean('allow_take_away')->default(1)->nullable();
            $table->boolean('allow_delivery')->default(1)->nullable();
        });
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('allow_dine_in')->default(1)->nullable();
            $table->dropColumn('allow_take_away')->default(1)->nullable();
            $table->dropColumn('allow_delivery')->default(1)->nullable();
        });
        Schema::table('menus', function (Blueprint $table) {
            $table->boolean('allow_dine_in')->default(1)->nullable();
            $table->boolean('allow_take_away')->default(1)->nullable();
            $table->boolean('allow_delivery')->default(1)->nullable();
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
