<?php

use Illuminate\Http\Response;
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

Route::group(
  [
    'prefix' => 'users'
  ], 
  function () {
  Route::post('register', 'API\UserController@register');
  Route::get('user/{id}', 'API\UserController@getUserById');
  Route::post('edit/{id}', 'API\UserController@editUser');
  Route::get('login', 'API\UserController@userLogin');
});

Route::group(
  [
    'prefix' => 'bills',
    'middleware' => [App\Http\Middleware\CheckToken::class],
  ], 
  function () {
  Route::get('bill/{id}', 'API\BillController@getUserBills');
  Route::post('create', 'API\BillController@createUserBill');
  Route::post('edit/{id}', 'API\BillController@editBill');
  Route::get('delete/{id}', 'API\BillController@deleteBill');
});

Route::group(
  [
    'prefix' => 'expenses'
  ], 
  function () {
  Route::get('user/{id}', 'API\ExpensesController@getUserExpenses');
  Route::get('bill/{id}', 'API\ExpensesController@getBillExpenses');
  Route::get('category/{id}', 'API\ExpensesController@getCategoryExpenses');
  Route::get('tag/{id}', 'API\ExpensesController@getTagExpenses');
  Route::post('create', 'API\ExpensesController@createExpense');
  Route::post('edit/{id}', 'API\ExpensesController@editExpense');
  Route::post('copy/{id}', 'API\ExpensesController@copyExpense');
  Route::get('delete/{id}', 'API\ExpensesController@deleteExpense');
});

Route::group(
  [
    'prefix' => 'incomes'
  ], 
  function () {
  Route::get('user/{id}', 'API\IncomesController@getUserIncomes');
  Route::get('bill/{id}', 'API\IncomesController@getBillIncomes');
  Route::get('source/{id}', 'API\IncomesController@getSourceIncomes');
  Route::get('tag/{id}', 'API\IncomesController@getTagIncomes');
  Route::post('create', 'API\IncomesController@createIncome');
  Route::post('edit/{id}', 'API\IncomesController@editIncome');
  Route::post('copy/{id}', 'API\IncomesController@copyIncome');
  Route::get('delete/{id}', 'API\IncomesController@deleteIncome');
});

Route::get('transactions/{id}', 'API\ExpensesController@getUserTransactions');
Route::get('transactions/bill/{id}', 'API\ExpensesController@getUserTransactionsByBill');

Route::group(
  [
    'prefix' => 'category'
  ], 
  function () {
  Route::get('user/{id}', 'API\CategoryController@getUserCategories');
  Route::post('create', 'API\CategoryController@createUserCategory');
  Route::post('edit/{id}', 'API\CategoryController@editCategory');
  Route::get('delete/{id}', 'API\CategoryController@deleteCategory');
});

Route::group(['prefix' => 'tag'], function () {
  Route::get('user/{id}', 'API\TagController@getUserTags');
  Route::post('create', 'API\TagController@createUserTag');
  Route::post('edit/{id}', 'API\TagController@editTag');
  Route::get('delete/{id}', 'API\TagController@deleteTag');
});

Route::group(
  [
    'prefix' => 'limit'
  ], 
  function () {
  Route::get('user/{id}', 'API\LimitController@getUserLimits');
  Route::post('create', 'API\LimitController@createUserLimit');
  Route::post('edit/{id}', 'API\LimitController@editLimit');
  Route::get('delete/{id}', 'API\LimitController@deleteLimit');
});

Route::group(
  [
    'prefix' => 'source'
  ], 
  function () {
  Route::get('user/{id}', 'API\SourceController@getUserSources');
  Route::post('create', 'API\SourceController@createUserSource');
  Route::post('edit/{id}', 'API\SourceController@editSource');
  Route::get('delete/{id}', 'API\SourceController@deleteSource');
});

Route::group(
  [
    'prefix' => 'goal',
    // 'middleware' => [App\Http\Middleware\CheckToken::class],
  ], 
  function () {
  Route::get('user/{id}', 'API\GoalController@getUserGoals');
  Route::post('create', 'API\GoalController@createUserGoal');
  Route::post('edit/{id}', 'API\GoalController@editUserGoal');
  Route::post('end/{id}', 'API\GoalController@endUserGoal');
  Route::get('delete/{id}', 'API\GoalController@deleteSource');
});