<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('qty')->nullable();
            $table->text('note')->nullable();
            $table->string('type')->nullable();
            $table->integer('stock')->nullable();
            $table->dateTime('datetime')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('menu_id')->nullable();
            $table->foreign("menu_id")->references('id')->on('menus')->nullOnDelete();
            $table->foreign("admin_id")->references('id')->on('admin_users')->nullOnDelete();
            
            

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
        Schema::dropIfExists('menu_stocks');
    }
}
