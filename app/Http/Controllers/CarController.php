<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Redirect;

class CarController extends Controller
{


  public function View () {

    if (Session::has('id')){
      if (session()->has('car_id')){
        session()->forget('car_id');
      }
      return view('add_edit_car');
    }

    return Redirect::to('/');
  }


  public function Edit ($car_id) {

    if (Session::has('id')){
      $car = \App\Car::find($car_id);
      if($car->user_id == session('id')){
        return Redirect::to('/EditCarView')->with('car_id', $car_id);
      }
    }

    return Redirect::to('/');
  }


  public function EditView () {

    if (Session::has('car_id')){

      $car = \App\Car::find(session('car_id'));
      session()->forget('car_id');

      return view('add_edit_car', ['car' => $car]);
    }

    return Redirect::to('/');
  }


  public function Info ($car_id) {

    if (Session::has('id')){
      $car = \App\Car::find($car_id);
      if($car->user_id == session('id')){
        return Redirect::to('/InfoCarView')->with('car_id', $car_id);
      }
    }

    return Redirect::to('/');
  }


  public function InfoView () {

    if (Session::has('car_id')){

      $car = \App\Car::find(session('car_id'));
      $costs = \App\Car::getCosts(session('car_id'));

      $fuel = \App\Fuel_expense::getByDate(session('car_id'))->get();
      $f_size = sizeof($fuel);

      $reminder = \App\Reminder::currentByDate($car->mileage_current)->get()->toArray();
      $r_size = sizeof($reminder);
      usort($reminder, 'sort_by_date');

      $service = \App\Service::getByDate(session('car_id'))->get();
      $s_size = sizeof($service);

      $expense = \App\Expense::getByDate(session('car_id'))->get();
      $e_size = sizeof($expense);

      session()->forget('car_id');

      return view('info', compact('car', 'costs',
                                  'fuel', 'f_size',
                                  'reminder', 'r_size',
                                  'service', 's_size',
                                  'expense', 'e_size'));
    }

    return Redirect::to('/');
  }


  public function AddEdit (Request $request) {

    $formData = $request->all();


    //Validation rules
    $rules = [
      'make' => 'required|alpha',
      'model' => 'required|regex:/^[A-Za-z0-9., ]+$/',
      'production_year' => 'required|between:1910,2100|integer',
      'engine' => 'required|regex:/^[A-Za-z0-9., ]+$/',
      'hp' => 'required|numeric',
      'purchase_date' => 'required|date',
      'purchase_price' => 'required|numeric',
      'mileage_start' => 'required|numeric',
      'mileage_current' => 'required|numeric',

    ];

    //Custom error messages
    $messages = [
      'make.required' => 'Marka pojazdu jest wymagana',
      'make.alpha' => 'Marka pojazdu może składać się tylko z liter',
      'model.required' => 'Model pojazdu jest wymagany',
      'model.regex' => 'Podano niepoprawny model pojazdu',
      'production_year.required' => 'Rok produkcji jest wymagany',
      'production_year.between' => 'Podano niepoprawny rok produkcji',
      'production_year.integer' => 'Podano niepoprawny rok produkcji',
      'engine.required' => 'Wersja silnikowa jest wymagana',
      'engine.regex' => 'Podano niepoprawną wersję silnikową',
      'hp.required' => 'Moc silnika jest wymagana',
      'hp.numeric' => 'Podano niepoprawną moc silnika',
      'purchase_date.required' => 'Data zakupu jest wymagana',
      'purchase_date.date' => 'Podano niepoprawną datę zakupu',
      'purchase_price.required' => 'Cena zakupu jest wymagana',
      'purchase_price.numeric' => 'Podano niepoprawną cenę zakupu',
      'mileage_start.required' => 'Przebieg początkowy jest wymagany',
      'mileage_start.numeric' => 'Podano niepoprawny przebieg początkowy',
      'mileage_current.required' => 'Przebieg aktualny jest wymagany',
      'mileage_current.numeric' => 'Podano niepoprawny przebieg aktualny',
    ];


    //Create a new validation instance.
    $validator = \Validator::make($formData, $rules, $messages);

    //if validator passes
    if($validator->passes()) {

      $car = new \App\Car;

      if($car->saveCar($formData)){

        //it destroys $car object
        unset($car);

        return Redirect::to('/');
      }

    }

    //if validator fails
    $error = $validator->messages();
    return back()->withErrors($validator)->with($formData);

  }


  public function Sell (Request $request) {

    $formData = $request->all();


    //Validation rules
    $rules = [
      'sale_date' => 'required|date',
      'sale_price' => 'required|numeric',
      'mileage_current2' => 'required|numeric',
    ];

    //Custom error messages
    $messages = [
      'sale_date.required' => 'Data sprzedaży jest wymagana',
      'sale_date.date' => 'Podano niepoprawną datę sprzedaży',
      'sale_price.required' => 'Cena sprzedaży jest wymagana',
      'sale_price.numeric' => 'Podano niepoprawną cenę sprzedaży',
      'mileage_current2.required' => 'Przebieg aktualny jest wymagany',
      'mileage_current2.numeric' => 'Podano niepoprawny przebieg aktualny',
    ];


    //Create a new validation instance.
    $validator = \Validator::make($formData, $rules, $messages);

    //if validator passes
    if($validator->passes()) {

      $car = new \App\Car;

      if($car->saleCar($formData)){

        //it destroys $car object
        unset($car);

        return Redirect::to('/');
      }

    }

    //if validator fails
    $error = $validator->messages();
    return back()->withErrors($validator)->with($formData);

  }


  public function Delete($car_id) {

    if (Session::has('id')){
      $car = \App\Car::find($car_id);
      if($car->user_id == session('id')){
        \App\Car::destroy($car_id);
      }
    }

    return Redirect::to('/');
  }


}
