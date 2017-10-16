<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CarlogController extends Controller
{
    public function login () {

      /*
       * dodać IF sprawdzającego czy jesteśmy zalogowani
       * if true - przekierowywuje na stronę startową
       * if false - przekierowuje na login page
       */

      return view('login');
    }

}
