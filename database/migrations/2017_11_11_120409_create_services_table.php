<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('car_id')->unsigned();
            $table->foreign('car_id')->references('id')->on('cars');
            $table->date('date');
            $table->mediumInteger('mileage')->unsigned();
            $table->string('description');
            $table->decimal('price_parts'); //default decimal('name',8,2)
            $table->decimal('price_labour'); //default decimal('name',8,2)
            $table->decimal('price_total'); //default decimal('name',8,2)
            $table->string('comment')->nullable();
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
        Schema::table('expenses', function($table){
            $table->dropForeign(['car_id']);
        });

        Schema::dropIfExists('services');
    }
}
