<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Fuel_expense extends Model
{

  /*
  * Enables softDeletes of Cars in database
  */
  use SoftDeletes;


  /*
  * Eloquent Relationship
  * Fuel_expense __belongs_to__ Car
  */
  public function car() {

    return $this->belongsTo(Car::class);
  }



  /*
  * it saves fuel in database
  * if session had 'fuel_id' then it saves changes in existing fuel entry
  */
  public function savefuel($formData){

    if (session()->has('fuel_id')){
      $fuel = \App\Fuel_expense::find(session('fuel_id'));
      session()->forget('fuel_id');
    }else{
      $fuel = new \App\Fuel_expense;
      $fuel->car_id = $formData['car_id'];
    }

    $fuel->date = $formData['date'];
    $fuel->fuel = $formData['fuel'];
    $fuel->litres = $formData['litres'];
    $fuel->price_all = $formData['price_all'];
    $fuel->price_l = $formData['price_l'];
    $fuel->mileage_current = $formData['mileage_current'];
    $fuel->distance = $formData['distance'];
    $fuel->fuel_consumption = $formData['fuel_consumption'];

    if ($fuel->save()){

      $car = \App\Car::find($formData['car_id']);

      $fuel_mileage = $car->fuel_mileage + $formData['distance'];
      $fuel_total = $car->fuel_total + $formData['litres'];
      $spendings_fuel = $car->spendings_fuel + $formData['price_all'];

      $avg = round((($fuel_total/$fuel_mileage)*100),2);

      if($car->mileage_current < $formData['mileage_current']){
          $car->mileage_current = $formData['mileage_current'];
      }

      $car->mileage_current = $formData['mileage_current'];
      $car->fuel_mileage = $fuel_mileage;
      $car->fuel_total = $fuel_total;
      $car->spendings_fuel = $spendings_fuel;
      $car->fuel_avg_consumption = $avg;


      if($car->save()){

        unset($fuel);
        unset($car);
        return true;
      }

      unset($car);
      return false;
    }

    unset($fuel);
    return false;
  }

}