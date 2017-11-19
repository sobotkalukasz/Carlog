<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Redirect;

class Fuel_expenseController extends Controller
{

  public function View () {

    if (Session::has('id'))
      if(\App\User::hasActiveCars(session('id'))){
        $cars = \App\Car::whereUser_id(session('id'))->get();
        return view('fuel', ['cars' => $cars]);
      }

    return Redirect::to('/');
  }


  public function Edit ($fuel_id) {

    if (Session::has('id')){

      $fuel = \App\Fuel_expense::find($fuel_id);
      $car = \App\Car::find($fuel->car_id);

      if($car->user_id == session('id')){
        return Redirect::to('/EditFuelView')->with('fuel_id', $fuel_id);
      }
    }

    return Redirect::to('/');
  }


  public function EditView () {

    if (Session::has('fuel_id')){

      $cars = \App\Car::whereUser_id(session('id'))->get();
      $fuel = \App\Fuel_expense::find(session('fuel_id'));
      session()->forget('fuel_id');

      return view('fuel', compact('cars', 'fuel'));
    }

    return Redirect::to('/');
  }


  public function AddEdit (Request $request) {

    $formData = $request->all();


    //Validation rules
    $rules = [
      'date' => 'required|date',
      'litres' => 'required|numeric',
      'price_all' => 'required|numeric',
      'price_l' => 'required|numeric',
      'mileage_current' => 'required|numeric',
      'distance' => 'required|numeric',
      'fuel_consumption' => 'required|numeric'
    ];

    //Custom error messages
    $messages = [
      'date.required' => 'Data tankowania jest wymagana',
      'date.date' => 'Podano niepoprawną datę tankowania',
      'litres.required' => 'Ilość litrów jest wymagana',
      'litres.numeric' => 'Podano niepoprawną ilość litrów',
      'price_all.required' => 'Cena zakupu jest wymagana',
      'price_all.numeric' => 'Podano niepoprawną cenę zakupu',
      'price_l.required' => 'Cena za litr jest wymagana',
      'price_l.numeric' => 'Podano niepoprawną cenę za litr',
      'mileage_current.required' => 'Przebieg aktualny jest wymagany',
      'mileage_current.numeric' => 'Podano niepoprawny przebieg aktualny',
      'distance.required' => 'Pokonany dystans jest wymagany',
      'distance.numeric' => 'Podano niepoprawny dystans',
      'fuel_consumption.required' => 'Spalanie jest wymagane',
      'fuel_consumption.numeric' => 'Podano niepoprawne spalanie',
    ];

    //Create a new validation instance.
    $validator = \Validator::make($formData, $rules, $messages);

    //if validator passes
    if($validator->passes()){

        $fuel = new \App\Fuel_expense;

        if($fuel->saveFuel($formData)){

          //it destroys $fuel object
          unset($fuel);

          if (isset($formData['fuel_id']))
            return Redirect::route('info.car', $formData['car_id']);
          else
            return Redirect::to('/');
        }
    }

    //if validator fails
    $error = $validator->messages();
    return back()->withErrors($validator)->with($formData);

  }


  public function Delete($fuel_id) {

    if (Session::has('id')){

      $fuel = \App\Fuel_expense::find($fuel_id);
      $car = \App\Car::find($fuel->car_id);

      if ($car->user_id == session('id')){

        $car_id = $fuel->car_id;

        if($fuel->mileage_current == $car->mileage_current)
          $car->mileage_current = $car->mileage_current - $fuel->distance;

        $car->fuel_mileage = $car->fuel_mileage - $fuel->distance;
        $car->fuel_total = $car->fuel_total - $fuel->litres;
        $car->spendings_fuel = $car->spendings_fuel - $fuel->price_all;
        $car->fuel_avg_consumption = round((($car->fuel_total/$car->fuel_mileage)*100),2);


        if($car->save()){

          unset($fuel);
          unset($car);

          \App\Fuel_expense::destroy($fuel_id);

          return Redirect::route('info.car', $car_id);
        }

        unset($fuel);
        unset($car);

        return Redirect::route('info.car', $car_id);

      }
    }

    return Redirect::to('/');
  }


}
