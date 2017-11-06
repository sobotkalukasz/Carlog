<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Redirect;

class LoginRegisterController extends Controller
{
    /*
    * This function validates data from login form and
    * checks if email exists in database. Finally
    * it uses $user->login() to retrieves an user object
    * from database and passes values by session().
    */

    public function LoginFormValidation (Request $request) {

      $formData = $request->all();

      //Validation rules
      $rules = [
        'login' => 'required|email',
        'password' => 'required',
      ];

      //Error messages
      $messages = [
        'login.required' => 'Nie podano adresu email.',
        'login.email' => 'Podano niepoprawny adres email.',
        'password.required' => 'Hasło jest wymagane.',
      ];

      $validator = \Validator::make($formData, $rules, $messages);

      //if validator passes
      if($validator->passes()){

        //checks if 'email' exists in database
        $user = new \App\User;
        if($user->checkEmail($formData['login'])){
          if($user = $user->login($formData)){

            Session::put('id', $user[0]['id']);
            Session::put('name', $user[0]['name']);
            Session::put('email', $user[0]['email']);

            //it destroys $formData and $user object
            unset($formData, $user);

            Redirect::to('/');

          }

        }

        //User doesn't exist
        $formData['error_login'] = 'Podano niepoprawny login lub hasło.';
        return back()->with($formData);
      }

      //if validator fails
      $error = $validator->messages();
      return back()->withErrors($validator)->with($formData);

    }


    /*
    * This function validates data from register form and
    * checks if email exists in database. If email is unque it uses
    * $user->saveUser() function to put new user into database.
    * At the end it uses $user->login() to retrieves a new user object
    * from database and passes values by session().
    */

    public function RegisterFormValidation(Request $request) {
      $formData = $request->all();

      //Validation rules
      $rules = [
        'name' => 'required|alpha|min:3|max:20',
        'email' => 'required|email',
        'pass' => 'required|confirmed|min:6',
        'pass_confirmation' => 'required'
      ];

      //Custom error messages
      $messages = [
        'name.required' => 'Imię jest wymagane',
        'name.min' => 'Imię jest za krótkie (min 3 znaki).',
        'name.max' => 'Imię jest za długie (max 20 znaków).',
        'name.alpha' => 'Imię może sie składać tylko z liter.',
        'email.required' => 'Adres email jest wymagany.',
        'email.email' => 'Podano niepoprawny adres email.',
        'pass.required' => 'Hasło jest wymagane.',
        'pass.min' => 'Hasło jest za krótkie (min 6 znaków)',
        'pass.confirmed' => 'Podano różne hasła.',
        'pass_confirmation.required' => 'Hasło jest wymagane.'
      ];


      //Create a new validation instance.
      $validator = \Validator::make($formData, $rules, $messages);

      //if validator passes
      if($validator->passes()) {

        //it checks if 'email' is unique in database
        $user = new \App\User;
        if($user->checkEmail($formData['email'])) {

          //email isn't unique
          $formData['error_register'] = 'Adres email jest już zajęty.';
          return back()->with($formData);
        };

        //it saves new user into database
        if($user->saveUser($formData)){
          $formData['login'] = $formData['email'];
          $formData['password'] = $formData['pass'];

          //after successfuly insertion it logs in new user
          //$user = new \App\User;
          if($user = $user->login($formData)){

            $message = 'Witaj nowy użytkowniku w moim serwisie.';

            Session::put('id', $user[0]['id']);
            Session::put('name', $user[0]['name']);
            Session::put('email', $user[0]['email']);
            Session::put('message_for_user', $message);

            //it destroys $user object
            unset($user);
            unset($user);

            return Redirect::to('/');
          }
        }
      }

      //if validator fails
      $error = $validator->messages();
      return back()->withErrors($validator)->with($formData);
    }

}
