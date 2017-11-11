<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reminder extends Model
{

  /*
  * Enables softDeletes of Reminder in database
  */
  use SoftDeletes;


  /*
  * Eloquent Relationship
  * Reminder __belongs_to__ Car
  */
  public function car() {

    return $this->belongsTo(Car::class);
  }



  /*
  * it saves reminder into database
  *
  */
  public function saveReminder($formData){


    $reminder = new \App\Reminder;
    $reminder->car_id = $formData['car_id'];
    $reminder->date = $formData['reminder_date'];
    $reminder->mileage = $formData['reminder_mileage'];
    $reminder->comment = $formData['reminder_comment'];

    if ($reminder->save()){
      unset($reminder);
      return true;
    }

    unset($reminder);
    return false;

  }



}