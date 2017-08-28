<?php

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
Route::get('/', function () {
    return view('welcome');
});

Route::get('payment/{expenses_id}', [
    "uses" => 'PaymentController@store',
    "as" => 'payment'
]);
Route::get('editpayment/{payment_id}', [
    "uses" => 'PaymentController@show',
    "as" => 'editpayment'
]);

Route::post('deletepayment', 'AjaxController@deletepayment');

Route::post('deleteexpenses', 'AjaxController@deleteexpense');

Route::post('deleteuser', 'AjaxController@deleteuser');

Route::post('saverole', 'AjaxController@saverole');

Route::get('newpayment', 'PaymentController@index');

Route::post('addpayment', 'PaymentController@create');

Route::get('editpayment/{payment_id}', [
    "uses" => 'PaymentController@editform',
    "as" => 'editpayment'
]);

Route::post('editpayment', 'PaymentController@edit');

Route::get('editexpense/{expenses_id}', [
    "uses" => 'ExpensesController@editform',
    "as" => 'editexpense'
]);
Route::post('editexpense', 'ExpensesController@edit');

Route::get('newexpense', 'ExpensesController@createform');
Route::post('newexpense', 'ExpensesController@create');

Route::get('users', 'UserController@index')->name('users');

Route::get('newuser', 'UserController@register');
Route::post('newuser', 'UserController@create')->name('newuser');

Route::get('edituser/{user_id}', [
    "uses" => 'UserController@editform',
    "as" => 'edituser'
]);

Route::post('edituser', 'UserController@edit');

Route::get('adminpayment/{expenses_id}', [
    "uses" => 'PaymentController@show',
    "as" => 'adminpayment'
]);

Route::get('admineditpayment/{payment_id}', [
    "uses" => 'PaymentController@admineditform',
    "as" => 'admineditpayment'
]);
Route::post('admineditpayment', 'PaymentController@adminedit');

Auth::routes();
Route::get('/', 'HomeController@index')->name('home');

