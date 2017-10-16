<?php

namespace App;

use Eloquent;

class User extends Eloquent
{

  public function checkEmail($email){

    $id = \App\User::whereEmail($email)->get(['id']);

    if (empty($id[0]['id'])) return false;

    return true;

  }

  public function login($login){

    $user = \App\User::whereEmail($login['login'])->get();

    if (password_verify($login['password'], $user[0]['password']))
      return $user;
    return false;

  }

}
