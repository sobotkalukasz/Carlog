<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Redirect;

class ServiceController extends Controller
{

  public function View () {

    if (Session::has('id', 'name', 'email'))
      if(\App\User::hasActiveCars(session('id')))
        $cars = \App\Car::whereUser_id(session('id'))->get();
        return view('service', ['cars' => $cars]);

    return Redirect::to('/');
  }


  public function Edit ($service_id) {

    if (Session::has('id', 'name', 'email')){

      $service = \App\Service::find($service_id);
      $car = \App\Car::find($service->car_id);

      if($car->user_id == session('id')){
        return Redirect::to('/EditServiceView')->with('service_id', $service_id);
      }
    }

    return Redirect::to('/');
  }


  public function EditView () {

    if (Session::has('service_id')){

      $cars = \App\Car::whereUser_id(session('id'))->get();
      $service = \App\Service::find(session('service_id'));
      session()->forget('service_id');
    
      return view('service', compact('cars', 'service'));
    }

    return Redirect::to('/');
  }


  public function AddEdit (Request $request) {

    $formData = $request->all();


    //Validation rules
    $rules = [
      'service_date' => 'required|date',
      'service_mileage' => 'required|numeric',
      'service_description' => 'required|max:50|string',
      'service_price_parts' => 'required|numeric',
      'service_price_labour' => 'required|numeric',
      'service_price_total' => 'required|numeric',
    ];

    //Custom error messages
    $messages = [
      'service_date.required' => 'Data serwisu jest wymagana',
      'service_date.date' => 'Data serwisu jest nieprawidłowa',
      'service_mileage.required' => 'Przebieg pojazdu jest wymagany',
      'service_mileage.numeric' => 'Podano niepoprawny przebieg pojazdu',
      'service_description.required' => 'Opis jest wymagany',
      'service_description.string' => 'Użyto niedozwolonych znaków',
      'service_description.max' => 'Dozwolona ilość znaków wynosi 50',
      'service_price_parts.required' => 'Koszt jest wymagany',
      'service_price_parts.numeric' => 'Podano niepoprawny koszt',
      'service_price_labour.required' => 'Koszt jest wymagany',
      'service_price_labour.numeric' => 'Podano niepoprawny koszt',
      'service_price_total.required' => 'Koszt jest wymagany',
      'service_price_total.numeric' => 'Podano niepoprawny koszt',
    ];



    if ($formData['service_comment'] !=NULL){
      $rules = array_merge($rules, ['service_comment' => 'max:255|string',]);

      $messages = array_merge($messages, ['service_comment.string' => 'Użyto niedozwolonych znaków',
                  'service_comment.max' => 'Dozwolona ilość znaków wynosi 255',]);
    }

    if (isset($formData['reminder']) && $formData['reminder'] == 'on'){
          $rules = array_merge($rules, ['reminder_comment' => 'required|max:255|string',]);

          $messages = array_merge($messages, ['reminder_comment.required' => 'Treść przypomnienia jest wymagana',
                  'reminder_comment.string' => 'Użyto niedozwolonych znaków',
                  'reminder_comment.max' => 'Dozwolona ilość znaków wynosi 255',]);

          if ($formData['reminder_date'] !=NULL){
            $rules = array_merge($rules, ['reminder_date' => 'date|after:today']);

            $messages = array_merge($messages, ['reminder_date.date' => 'Podano niepoprawną datę przypomnienia',
                    'reminder_date.after' => 'Data przypomnienia musi być większa niż dzisiaj',]);
          }

          if ($formData['reminder_mileage'] !=NULL){
            $rules = array_merge($rules, ['reminder_mileage' => 'numeric',]);

            $messages = array_merge($messages, ['reminder_mileage.numeric' => 'Podano niepoprawny przebieg przypomnienia',]);
          }

    }

    //Create a new validation instance.
    $validator = \Validator::make($formData, $rules, $messages);

    //if validator passes
    if($validator->passes()) {

        $service = new \App\Service;

        if($service->saveService($formData)){

            //it destroys $service object
            unset($service);

            if (isset($formData['reminder']) && $formData['reminder'] == 'on'){
            $reminder = new \App\Reminder;

            if($reminder->saveReminder($formData)){

              //it destroys $reminder object
              unset($reminder);

              if (isset($formData['service_id']))
                return Redirect::route('info.car', $formData['car_id']);
              else
                return Redirect::to('/');
            }

            //it destroys $reminder object
            unset($reminder);
        }

        if (isset($formData['service_id']))
          return Redirect::route('info.car', $formData['car_id']);
        else
          return Redirect::to('/');

        }

        //it destroys $service object
        unset($service);
    }


    //if validator fails
    $error = $validator->messages();
    return back()->withErrors($validator)->with($formData);

  }


  public function Delete($service_id) {

    if (Session::has('id', 'name', 'email')){

      $service = \App\Service::find($service_id);
      $car = \App\Car::find($service->car_id);

      if($car->user_id == session('id')){

        $car_id = $service->car_id;
        $car->spendings_service = $car->spendings_service - $service->price_total;

        if($car->save()){

          unset($sercie);
          unset($car);

          \App\Service::destroy($service_id);

          return Redirect::route('info.car', $car_id);
        }

        unset($service);
        unset($car);

        return Redirect::route('info.car', $car_id);
      }
      unset($service);
      unset($car);
    }

    return Redirect::to('/');
  }



}
