<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Redirect;

class CarlogController extends Controller
{
    public function login () {

      if (Session::has('id', 'name', 'email'))
        return view('home');

      return view('login');
    }


    public function logout () {

      if (Session::has('id', 'name', 'email')){
        Session::flush();
      }

      return Redirect::to('/');
    }


}
