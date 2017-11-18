<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{

  /*
  * Enables softDeletes of Users in database
  */
  use SoftDeletes;

  /*
  * Eloquent Relationship
  * User __has_many__ Car
  */

  public function cars(){

    return $this->hasMany(Car::class);
  }



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

  public function getUserById($id){

    return \App\User::whereId($id)->get();
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


  /*
  * it returns all user cars (by users ID)
  */
  public function scopeGetCars($query, $id){

    return $query->find($id)->cars;
  }


  /*
  * it returns only current user's cars (by users ID)
  */
  public static function hasActiveCars($id){

    $cars = \App\Car::whereUser_id($id)
                    ->whereNull('sale_date')
                    ->get();



    return (!empty($cars->toArray()));
  }


}
