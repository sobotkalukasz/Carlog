<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Redirect;

class EntryController extends Controller
{

  public function AddEditFuel (Request $request) {

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

          return Redirect::to('/');
        }
    }

    //if validator fails
    $error = $validator->messages();
    return back()->withErrors($validator)->with($formData);

  }



  public function AddEditReminder (Request $request) {

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

        return Redirect::to('/');

      }

    }

    //if validator fails
    $error = $validator->messages();
    return back()->withErrors($validator)->with($formData);

  }





  public function AddEditExpense (Request $request) {

    $formData = $request->all();


    //Validation rules
    $rules = [
      'expense_date' => 'required|date',
      'expense_description' => 'required|max:50|string',
      'expense_price' => 'required|numeric',
    ];

    //Custom error messages
    $messages = [
      'expense_date.required' => 'Data poniesienia wydatku jest wymagana',
      'expense_date.date' => 'Data poniesienia wydatku jest nieprawidłowa',
      'expense_description.required' => 'Opis jest wymagany',
      'expense_description.string' => 'Użyto niedozwolonych znaków',
      'expense_description.max' => 'Dozwolona ilość znaków wynosi 50',
      'expense_price.required' => 'Kwota wydatku jest wymagana',
      'expense_price.numeric' => 'Podano niepoprawną kwotę',

    ];



    if ($formData['expense_comment'] !=NULL){
      $rules = array_merge($rules, ['expense_comment' => 'max:255|string',]);

      $messages = array_merge($messages, ['expense_comment.string' => 'Użyto niedozwolonych znaków',
                  'expense_comment.max' => 'Dozwolona ilość znaków wynosi 255',]);
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

      $expense = new \App\Expense;

      if($expense->saveExpense($formData)){

        //it destroys $expense object
        unset($expense);

        if (isset($formData['reminder']) && $formData['reminder'] == 'on'){
            $reminder = new \App\Reminder;

            if($reminder->saveReminder($formData)){

              //it destroys $reminder object
              unset($reminder);

              return Redirect::to('/');
            }

            //it destroys $reminder object
            unset($reminder);
        }

        return Redirect::to('/');

      }

      //it destroys $expense object
      unset($expense);


    }

    //if validator fails
    $error = $validator->messages();
    return back()->withErrors($validator)->with($formData);

  }




  public function AddEditService (Request $request) {

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

              return Redirect::to('/');
            }

            //it destroys $reminder object
            unset($reminder);
        }

        return Redirect::to('/');



        }

        //it destroys $service object
        unset($service);
    }


    //if validator fails
    $error = $validator->messages();
    return back()->withErrors($validator)->with($formData);

  }


  public function DeleteFuel($fuel_id) {

    if (Session::has('id', 'name', 'email')){
    //  \App\Fuel_expense::destroy($fuel_id);
    return 'Kod odejmowania przebiegu!';
    }
    return Redirect::to('/');
  }




}
