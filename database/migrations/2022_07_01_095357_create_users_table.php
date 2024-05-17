<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('language')->default("English")->nullable();
            $table->string('image')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('provider')->nullable();
            $table->string('password')->nullable();
            $table->string('gender')->nullable();
            $table->string('mobile_verified_at')->nullable();
            $table->string('email_verified_at')->nullable();
            $table->string('country_code')->nullable();
            $table->string('fcm_token')->nullable();
            $table->boolean('is_driver')->default(false)->nullable();
            $table->boolean('is_verified_driver')->default(false)->nullable();
            $table->string('uid')->nullable();
           
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
        Schema::dropIfExists('users');
    }
}
