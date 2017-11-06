<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->increments('id');
            $table->string('make');
            $table->string('model');
            $table->smallInteger('production_year')->unsigned();
            $table->string('engine');
            $table->smallInteger('hp')->unsigned();
            $table->enum('fuel', ['PB', 'ON', 'LPG']);
            $table->date('purchase_date');
            $table->date('sale_date')->nullable();
            $table->decimal('purchase_price'); //default decimal('name',8,2)
            $table->decimal('sale_price')->nullable(); //default decimal('name',8,2)
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cars', function($table){
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('cars');
    }
}
