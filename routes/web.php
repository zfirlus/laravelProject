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
Route::get('editPayment/{payment_id}', [
    "uses" => 'PaymentController@show',
    "as" => 'editPayment'
]);

Route::post('deletePayment', 'AjaxController@deletePayment');

Route::post('deleteExpenses', 'AjaxController@deleteExpense');

Route::post('deleteUser', 'AjaxController@deleteUser');

Route::post('saveRole', 'AjaxController@saveRole');

Route::get('newPayment', 'PaymentController@index');

Route::post('addPayment', 'PaymentController@create');

Route::get('editPayment/{payment_id}', [
    "uses" => 'PaymentController@editForm',
    "as" => 'editPayment'
]);

Route::post('editPayment', 'PaymentController@edit');

Route::get('editExpense/{expenses_id}', [
    "uses" => 'ExpensesController@editForm',
    "as" => 'editExpense'
]);
Route::post('editExpense', 'ExpensesController@edit');

Route::get('newExpense', 'ExpensesController@createForm');
Route::post('newExpense', 'ExpensesController@create');

Route::get('users', 'UserController@index')->name('users');

Route::get('newUser', 'UserController@register');
Route::post('newUser', 'UserController@create')->name('newUser');

Route::get('editUser/{user_id}', [
    "uses" => 'UserController@editForm',
    "as" => 'editUser'
]);

Route::post('editUser', 'UserController@edit');

Route::get('adminPayment/{expenses_id}', [
    "uses" => 'PaymentController@show',
    "as" => 'adminPayment'
]);

Route::get('adminEditPayment/{payment_id}', [
    "uses" => 'PaymentController@adminEditForm',
    "as" => 'adminEditPayment'
]);
Route::post('adminEditPayment', 'PaymentController@adminEdit');

Auth::routes();
Route::get('/', 'HomeController@index')->name('home');



