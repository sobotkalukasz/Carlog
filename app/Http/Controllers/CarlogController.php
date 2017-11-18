<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Redirect;

class CarlogController extends Controller
{

    public function home () {

      if (Session::has('id', 'name', 'email')){
        $cars = \App\Car::whereUser_id(session('id'))->get();
        $size = sizeof($cars);
  
        return view('home', compact('cars', 'size'));
      }

      return view('login');
    }


    public function logout () {

      if (Session::has('id', 'name', 'email')){
        Session::flush();
      }

      return Redirect::to('/');
    }


}
