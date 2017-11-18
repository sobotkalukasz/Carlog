<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Redirect;

class ExpenseController extends Controller
{

  public function View () {

    if (Session::has('id', 'name', 'email'))
      if(\App\User::hasActiveCars(session('id'))){
        $cars = \App\Car::whereUser_id(session('id'))->get();
        return view('expense', ['cars' => $cars]);
      }

    return Redirect::to('/');
  }


  public function Edit ($expense_id) {

    if (Session::has('id', 'name', 'email')){

      $expense = \App\Expense::find($expense_id);
      $car = \App\Car::find($expense->car_id);

      if($car->user_id == session('id')){
        return Redirect::to('/EditExpenseView')->with('expense_id', $expense_id);
      }
    }

    return Redirect::to('/');
  }


  public function EditView () {

    if (Session::has('expense_id')){

      $cars = \App\Car::whereUser_id(session('id'))->get();
      $expense = \App\Expense::find(session('expense_id'));
      session()->forget('expense_id');

      return view('expense', compact('cars', 'expense'));
    }

    return Redirect::to('/');
  }


  public function AddEdit (Request $request) {

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

              if (isset($formData['expense_id']))
                return Redirect::route('info.car', $formData['car_id']);
              else
                return Redirect::to('/');
            }

            //it destroys $reminder object
            unset($reminder);
        }

        if (isset($formData['expense_id']))
          return Redirect::route('info.car', $formData['car_id']);
        else
          return Redirect::to('/');

      }

      //it destroys $expense object
      unset($expense);


    }

    //if validator fails
    $error = $validator->messages();
    return back()->withErrors($validator)->with($formData);

  }


  public function Delete($expense_id) {

    if (Session::has('id', 'name', 'email')){

      $expense = \App\Expense::find($expense_id);
      $car = \App\Car::find($expense->car_id);

      if($car->user_id == session('id')){

        $car_id = $car->id;
        $car->spendings_others = $car->spendings_others - $expense->price;

        if($car->save()){

          unset($expense);
          unset($car);

          \App\Expense::destroy($expense_id);

          return Redirect::route('info.car', $car_id);
        }

        unset($expense);
        unset($car);

        return Redirect::route('info.car', $car_id);
      }
      unset($expense);
      unset($car);
    }

    return Redirect::to('/');
  }


}
