<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePremiumPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('premium_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('color')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
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
        Schema::dropIfExists('premium_permissions');
    }
}
