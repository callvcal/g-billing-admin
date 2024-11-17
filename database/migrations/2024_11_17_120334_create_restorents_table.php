<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestorentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restorents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->string('address')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('restorent_types')->nullable();
            $table->string('tags')->nullable();
            $table->string('food_type')->nullable();
            $table->string('serve_type')->nullable();
            $table->string('legan_name')->nullable();
            $table->string('gst_number')->nullable();
            $table->text('gst_file')->nullable();
            $table->string('fssai_lic_no')->nullable();
            $table->text('fssai_lic_file')->nullable();
            $table->date('fssai_lic_no_expiry')->nullable();
            $table->string('mobile')->nullable();
            $table->string('owner_name')->nullable();
            $table->decimal('latitude',10,8)->nullable();
            $table->decimal('longitude',10,8)->nullable();
            $table->boolean('allow_breakfast')->nullable();
            $table->boolean('allow_lunch')->nullable();
            $table->boolean('allow_dinner')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->boolean('active')->default('1')->nullable();
            $table->string('restorent_id')->nullable();
            $table->boolean('is_verified')->nullable();
            $table->foreign("admin_id")->references('id')->on('admin_users')->nullOnDelete();
            $table->foreign("business_id")->references('id')->on('businesses')->nullOnDelete();
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
        Schema::dropIfExists('restorents');
    }
}
