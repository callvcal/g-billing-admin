<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRawMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->foreign('business_id')->references('id')->on('businesses')->nullOnDelete();
            $table->dateTime('datetime')->nullable();
            $table->integer('qty')->nullable();
            $table->double('amount')->nullable();
            $table->string('type')->comment('stock in, stock out')->nullable();
            $table->foreign("admin_id")->references('id')->on('admin_users')->nullOnDelete();
            $table->foreign("unit_id")->references('id')->on('units')->nullOnDelete();
            $table->unsignedBigInteger('material_id')->nullable();
            $table->foreign("material_id")->references('id')->on('materials')->nullOnDelete();
            $table->text('note')->nullable();

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
        Schema::dropIfExists('raw_materials');
    }
}
