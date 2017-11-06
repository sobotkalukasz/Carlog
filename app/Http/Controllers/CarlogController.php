<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Redirect;

class CarlogController extends Controller
{
    public function home () {

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


    public function AddCarView () {

      if (Session::has('id', 'name', 'email')){
        if (session()->has('car_id')){
          session()->forget('car_id');
        }
        return view('add_edit_car');
      }

      return Redirect::to('/');
    }


    public function EditCarView ($car_id) {

      if (Session::has('id', 'name', 'email')){

        Session::put('car_id', $car_id);
        return Redirect::to('/EditCarView');


      }

      return Redirect::to('/');
    }

}
