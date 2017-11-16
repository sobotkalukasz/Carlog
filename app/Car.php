<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Car extends Model
{

  /*
  * Enables softDeletes of Cars in database
  */
  use SoftDeletes;

  /*
  * Eloquent Relationship
  * Car __belongs_to__ User
  */
  public function user() {

    return $this->belongsTo(User::class);
  }


  /*
  * Eloquent Relationship
  * Car __has_many__ Fuel_expense
  */
  public function fuel_expenses(){

    return $this->hasMany(Fuel_expense::class);
  }


  /*
  * Eloquent Relationship
  * Car __has_many__ Reminders
  */
  public function reminders(){

    return $this->hasMany(Reminder::class);
  }


  /*
  * Eloquent Relationship
  * Car __has_many__ Expenses
  */
  public function expenses(){

    return $this->hasMany(Expense::class);
  }


  /*
  * Eloquent Relationship
  * Car __has_many__ Services
  */
  public function services(){

    return $this->hasMany(Service::class);
  }



  /*
  * it saves car into database
  * if session had 'car_id' then it saves changes in existing car
  */
  public function saveCar($formData){

    if (session()->has('car_id')){
      $car = \App\Car::find(session('car_id'));
      session()->forget('car_id');
    }else{
      $car = new \App\Car;
      $car->user_id = session('id');
    }

    $car->make = $formData['make'];
    $car->model = $formData['model'];
    $car->production_year = $formData['production_year'];
    $car->engine = $formData['engine'];
    $car->hp = $formData['hp'];
    $car->fuel = $formData['fuel'];
    $car->purchase_date = $formData['purchase_date'];
    $car->purchase_price = $formData['purchase_price'];
    $car->mileage_start = $formData['mileage_start'];
    $car->mileage_current = $formData['mileage_current'];

    if ($car->save()){
      unset($car);
      return true;
    }

    unset($car);
    return false;

  }


  /*
  * it saves 'sale_price', 'sale_date' and 'mileage_current'
  * in existing car
  */
  public function saleCar($formData){

    $car = \App\Car::find(session('car_id'));
    session()->forget('car_id');

    $car->sale_date = $formData['sale_date'];
    $car->sale_price = $formData['sale_price'];
    $car->mileage_current = $formData['mileage_current2'];

    if ($car->save()){
      unset($car);
      return true;
    }

    unset($car);
    return false;

  }

  /*
  * it returns car object by its ID
  */
  public static function getCarById($id){

    return \App\Car::whereId($id)->get();
  }


}
