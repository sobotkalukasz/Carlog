<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cars', function($table) {
          $table->mediumInteger('mileage_start')->unsigned();
          $table->mediumInteger('mileage_current')->unsigned();
          $table->mediumInteger('fuel_mileage')->unsigned()->nullable(); //milage from fuel expenses
          $table->decimal('fuel_total')->nullable(); //default decimal('name',8,2)
          $table->decimal('fuel_avg_consumption')->nullable(); //default decimal('name',8,2)
          $table->decimal('spendings_fuel')->nullable(); //default decimal('name',8,2)
          $table->decimal('spendings_service')->nullable(); //default decimal('name',8,2)
          $table->decimal('spendings_others')->nullable(); //default decimal('name',8,2)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cars', function($table) {
          $table->dropColumn('mileage_start');
          $table->dropColumn('mileage_current');
          $table->dropColumn('fuel_milage');
          $table->dropColumn('fuel_total');
          $table->dropColumn('fuel_avg_consumption');
          $table->dropColumn('spendings_fuel');
          $table->dropColumn('spendings_service');
          $table->dropColumn('spendings_others');
        });
    }
}
