<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

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

Route::get('/', 'CarlogController@home');
Route::get('/Logout', 'CarlogController@logout');

Route::get('/AddCar', 'CarlogController@AddCarView');
Route::get('/EditCarById/{id}', 'CarlogController@EditCarView')->name('edit.car');
Route::get('/DeleteCarById/{id}', 'CarController@DeleteCar')->name('delete.car');
Route::get('/InfoCarById/{id}', 'CarlogController@InfoCarView')->name('info.car');

Route::get('/Fuel', 'CarlogController@FuelView');
Route::get('/EditFuelById/{id}', 'CarlogController@EditFuelView')->name('edit.fuel');
Route::get('/DeleteFuelById/{id}', 'EntryController@DeleteFuel')->name('delete.fuel');

Route::get('/Service', 'CarlogController@ServiceView');
Route::get('/EditServiceById/{id}', 'CarlogController@EditServiceView')->name('edit.service');
Route::get('/DeleteServiceById/{id}', 'EntryController@DeleteService')->name('delete.service');

Route::get('/Reminder', 'CarlogController@ReminderView');
Route::get('/Expense', 'CarlogController@ExpenseView');



Route::get('/EditCarView', function() {
  return view('add_edit_car');
});

Route::get('/InfoCarView', function() {
  return view('info');
});

Route::get('/EditFuelView', function() {
  return view('fuel');
});

Route::get('/EditServiceView', function() {
  return view('service');
});


Route::post('/LoginFormValidation', 'LoginRegisterController@LoginFormValidation');
Route::post('/RegisterFormValidation', 'LoginRegisterController@RegisterFormValidation');

Route::post('/AddEditCar', 'CarController@AddEditCar');
Route::post('/SellCar', 'CarController@SellCar');

Route::post('/AddEditFuel', 'EntryController@AddEditFuel');
Route::post('/AddEditReminder', 'EntryController@AddEditReminder');
Route::post('/AddEditExpense', 'EntryController@AddEditExpense');
Route::post('/AddEditService', 'EntryController@AddEditService');
