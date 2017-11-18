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


//User
Route::post('/LoginFormValidation', 'UserController@LoginFormValidation');
Route::post('/RegisterFormValidation', 'UserController@RegisterFormValidation');


//Car
Route::get('/AddCar', 'CarController@View');
Route::get('/EditCarById/{id}', 'CarController@EditView')->name('edit.car');
Route::get('/EditCarView', function() {return view('add_edit_car');});
Route::get('/DeleteCarById/{id}', 'CarController@Delete')->name('delete.car');
Route::get('/InfoCarById/{id}', 'CarController@InfoView')->name('info.car');
Route::get('/InfoCarView', function() {return view('info');});
Route::post('/AddEditCar', 'CarController@AddEdit');
Route::post('/SellCar', 'CarController@Sell');


//Fuel
Route::get('/Fuel', 'Fuel_expenseController@View');
Route::get('/EditFuelById/{id}', 'Fuel_expenseController@Edit')->name('edit.fuel');
Route::get('/EditFuelView', 'Fuel_expenseController@EditView');
Route::get('/DeleteFuelById/{id}', 'Fuel_expenseController@Delete')->name('delete.fuel');
Route::post('/AddEditFuel', 'Fuel_expenseController@AddEdit');


//Service
Route::get('/Service', 'ServiceController@View');
Route::get('/EditServiceById/{id}', 'ServiceController@Edit')->name('edit.service');
Route::get('/EditServiceView', 'ServiceController@EditView');
Route::get('/DeleteServiceById/{id}', 'ServiceController@Delete')->name('delete.service');
Route::post('/AddEditService', 'ServiceController@AddEdit');


//Reminder
Route::get('/Reminder', 'ReminderController@View');
Route::get('/EditReminderById/{id}', 'ReminderController@Edit')->name('edit.reminder');
Route::get('/EditReminderView', 'ReminderController@EditView');
Route::get('/DeleteReminderById/{id}', 'ReminderController@Delete')->name('delete.reminder');
Route::post('/AddEditReminder', 'ReminderController@AddEdit');


//Expense
Route::get('/Expense', 'ExpenseController@View');
Route::get('/EditExpenseById/{id}', 'ExpenseController@Edit')->name('edit.expense');
Route::get('/EditExpenseView', 'ExpenseController@EditView');
Route::get('/DeleteExpenseById/{id}', 'ExpenseController@Delete')->name('delete.expense');
Route::post('/AddEditExpense', 'ExpenseController@AddEdit');
