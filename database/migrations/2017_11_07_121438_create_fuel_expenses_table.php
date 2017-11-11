<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuelExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('car_id')->unsigned();
            $table->foreign('car_id')->references('id')->on('cars');
            $table->date('date');
            $table->enum('fuel', ['PB', 'ON', 'LPG']);
            $table->decimal('litres'); //default decimal('name',8,2)
            $table->decimal('price_all'); //default decimal('name',8,2)
            $table->decimal('price_l'); //default decimal('name',8,2)
            $table->mediumInteger('mileage_current')->unsigned();
            $table->mediumInteger('distance')->unsigned();
            $table->decimal('fuel_consumption'); //default decimal('name',8,2)
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
        Schema::table('fuel_expenses', function($table){
            $table->dropForeign(['car_id']);
          });

        Schema::dropIfExists('fuel_expenses');
    }
}
