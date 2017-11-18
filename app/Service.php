<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{

  /*
  * Enables softDeletes of Expense in database
  */
  use SoftDeletes;


  /*
  * Eloquent Relationship
  * Service __belongs_to__ Car
  */
  public function car() {

    return $this->belongsTo(Car::class);
  }


  /*
  * it saves expense into database
  * if $formData had 'service_id' then it saves changes in existing service entry
  */
  public function saveService($formData){

      if (isset($formData['service_id'])){
        $service = \App\Service::find($formData['service_id']);
        $price = $service->price_total;
      }
      else{
        $service = new \App\Service;
        $service->car_id = $formData['car_id'];
      }


      $service->date = $formData['service_date'];
      $service->mileage = $formData['service_mileage'];
      $service->description = $formData['service_description'];
      $service->price_parts = $formData['service_price_parts'];
      $service->price_labour = $formData['service_price_labour'];
      $service->price_total = $formData['service_price_total'];
      $service->comment = $formData['service_comment'];

      if ($service->save()){

        $car = \App\Car::find($formData['car_id']);

        if (isset($formData['service_id'])){
            if ($price != $formData['service_price_total']){
              $spendings_service = $car->spendings_service + ($formData['service_price_total'] - $price);
            }
            else{
              $spendings_service = $car->spendings_service;
            }

        }
        else{
          $spendings_service = $car->spendings_service + $formData['service_price_total'];
        }

        $car->spendings_service = $spendings_service;

        if ($car->save()){

          unset($service);
          unset($car);
          return true;

        }

        unset($service);
        return false;
      }

      unset($service);
      return false;
  }


  public static function scopeGetByDate($query, $id){

    return $query->whereCar_id($id)
                  ->orderBy('date', 'desc');
  }


}
