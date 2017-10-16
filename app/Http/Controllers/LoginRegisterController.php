<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginRegisterController extends Controller
{

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

            //it destroys $formData
            unset($formData);

            return $user[0]['name'];
          }
          
        }

        //User doesn't exists
        $formData['error_login'] = 'Podano niepoprawny login lub hasło.';
        return back()->with($formData);
      }

      //if validator fails
      $error = $validator->messages();
      return back()->withErrors($validator)->with($formData);

    }



    public function RegisterFormValidation(Request $request) {
      $formData = $request->all();

      //Validation rules
      $rules = [
        'name' => 'required|alpha|min:3|max:20',
        'email' => 'required|email',
        'pass' => 'required|confirmed|min:6',
        'pass_confirmation' => 'required'
      ];

      //Error messages
      $messages = [
        'name.required' => 'Imię jest wymagane',
        'name.min' => 'Imię jest za krótkie (min 3 znaki).',
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

        //it checks if 'email' is unique
        $user = new \App\User;
        if($user->checkEmail($formData['email'])) {

          //email isn't unique
          $formData['error_register'] = 'Adres email jest już zajęty.';
          return back()->with($formData);
        };

        $pass_hash = $formData['pass'];
        $formData['pass'] = password_hash($formData['pass'], PASSWORD_DEFAULT);

        $user = new \App\User;
        $user->name = $formData['name'];
        $user->email = $formData['email'];
        $user->password = $formData['pass'];
        $user->save();

        //it destroys $formData
        unset($formData);

        return "Data was saved";

      }

      //if validator fails
      $error = $validator->messages();
      return back()->withErrors($validator)->with($formData);

    }

}
