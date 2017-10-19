<?php

namespace App;

use Eloquent;

class User extends Eloquent
{

  /*
  * It checks if email adress is unique in database
  * Returns true if exists and false if doesn't exist.
  */

  public function checkEmail($email){

    $id = \App\User::whereEmail($email)->get(['id']);

    if (empty($id[0]['id'])) return false;

    return true;

  }


  /*
  * It searches user in database by email (login) and verifies the password.
  * If password is correct returns an user object and if not returns false
  */

  public function login($login){

    $user = \App\User::whereEmail($login['login'])->get();

    if (password_verify($login['password'], $user[0]['password']))
      return $user;
    return false;

  }


  /*
  * It hashes the password and saves new user to database.
  * If fails returns false
  */

  public function saveUser($formData){

    $formData['pass'] = password_hash($formData['pass'], PASSWORD_DEFAULT);
    $user = new \App\User;
    $user->name = $formData['name'];
    $user->email = $formData['email'];
    $user->password = $formData['pass'];

    if ($user->save()){
      unset($user);
      return true;
    }

    unset($user);
    return false;

  }


}
