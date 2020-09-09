<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('register', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'users'], function () {
  Route::post('register', 'API\UserController@register');
  Route::get('user/{id}', 'API\UserController@getUserById');
  Route::post('edit/{id}', 'API\UserController@editUser');
  Route::get('login', 'API\UserController@userLogin');
});

Route::group(['prefix' => 'bills'], function () {
  Route::get('bill/{id}', 'API\BillController@getUserBills');
  Route::post('create', 'API\BillController@createUserBill');
  Route::post('edit/{id}', 'API\BillController@editBill');
  Route::get('delete/{id}', 'API\BillController@deleteBill');
});