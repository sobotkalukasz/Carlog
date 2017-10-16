<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'CarlogController@login');

Route::post('/LoginFormValidation', 'LoginRegisterController@LoginFormValidation');

Route::post('/RegisterFormValidation', 'LoginRegisterController@RegisterFormValidation');

//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
