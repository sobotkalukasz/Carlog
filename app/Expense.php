<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{

  /*
  * Enables softDeletes of Expense in database
  */
  use SoftDeletes;


  /*
  * Eloquent Relationship
  * Expense __belongs_to__ Car
  */
  public function car() {

    return $this->belongsTo(Car::class);
  }


  /*
  * it saves expense into database
  * if $formData had 'expense_id' then it saves changes in existing service entry
  */
  public function saveExpense($formData){


    if (isset($formData['expense_id'])){
      $expense = \App\Expense::find($formData['expense_id']);
      $price = $expense->price;
    }
    else{
      $expense = new \App\Expense;
      $expense->car_id = $formData['car_id'];
    }

    $expense->date = $formData['expense_date'];
    $expense->description = $formData['expense_description'];
    $expense->price = $formData['expense_price'];
    $expense->comment = $formData['expense_comment'];

    if ($expense->save()){

      $car = \App\Car::find($formData['car_id']);

      if (isset($formData['expense_id'])){
          if ($price != $formData['expense_price']){
            $spendings_others = $car->spendings_others + ($formData['expense_price'] - $price);
          }
          else{
            $spendings_others = $car->spendings_others;
          }
      }
      else{
        $spendings_others = $car->spendings_others + $formData['expense_price'];
      }

      $car->spendings_others = $spendings_others;

      if ($car->save()){

        unset($expense);
        unset($car);
        return true;

      }

      unset($expense);
      return false;
    }

    unset($expense);
    return false;

  }


}
