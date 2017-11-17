<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Redirect;

class ReminderController extends Controller
{

  public function View () {

    if (Session::has('id', 'name', 'email'))
      if(\App\User::hasActiveCars(session('id')))
        return view('reminder');

    return Redirect::to('/');
  }


  public function EditView ($reminder_id) {

    if (Session::has('id', 'name', 'email')){

      $reminder = \App\Reminder::find($reminder_id);
      $car = \App\Car::find($reminder->car_id);

      if($car->user_id == session('id')){
        Session::put('reminder_id', $reminder_id);
        return Redirect::to('/EditReminderView');
      }
    }

    return Redirect::to('/');
  }


  public function AddEdit (Request $request) {

    $formData = $request->all();


    //Validation rules
    $rules = [
      'reminder_comment' => 'required|max:255|string',
    ];

    if ($formData['reminder_date'] !=NULL){
      $rules = array_merge($rules, ['reminder_date' => 'date|after:today']);
    }

    if ($formData['reminder_mileage'] !=NULL){
      $rules = array_merge($rules, ['reminder_mileage' => 'numeric']);
    }

    //Custom error messages
    $messages = [
      'reminder_date.date' => 'Podano niepoprawną datę przypomnienia',
      'reminder_date.after' => 'Data przypomnienia musi być większa niż dzisiaj',
      'reminder_mileage.numeric' => 'Podano niepoprawny przebieg aktualny',
      'reminder_comment.required' => 'Treść przypomnienia jest wymagana',
      'reminder_comment.string' => 'Użyto niedozwolonych znaków',
      'reminder_comment.max' => 'Dozwolona ilość znaków wynosi 255',
    ];

    //Create a new validation instance.
    $validator = \Validator::make($formData, $rules, $messages);

    //if validator passes
    if($validator->passes()) {

      $reminder = new \App\Reminder;

      if($reminder->saveReminder($formData)){

        //it destroys $reminder object
        unset($reminder);

        if (isset($formData['reminder_id']))
          return Redirect::route('info.car', $formData['car_id']);
        else
          return Redirect::to('/');

      }

    }

    //if validator fails
    $error = $validator->messages();
    return back()->withErrors($validator)->with($formData);

  }


  public function Delete($reminder_id) {

    if (Session::has('id', 'name', 'email')){

        $reminder = \App\Reminder::find($reminder_id);
        $car = \App\Car::find($reminder->car_id);

        if($car->user_id == session('id')){
          $car_id = $reminder->car_id;
          unset($reminder);
          unset($car);

          \App\Reminder::destroy($reminder_id);

          return Redirect::route('info.car', $car_id);
        }
        unset($reminder);
        unset($car);
    }

    return Redirect::to('/');
  }

}