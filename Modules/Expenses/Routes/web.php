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
Route::prefix('exchangeBonds')->group(function() {
    Route::get('manage', 'ExchangeBondController@manage');
    Route::get('datatable', 'ExchangeBondController@datatable');
});
Route::resource('exchangeBonds', ExchangeBondController::class);

Route::prefix('expenses')->group(function() {
    Route::get('manage', 'ExpensesController@manage');
    Route::get('datatable', 'ExpensesController@datatable');
});
Route::resource('expenses', ExpensesController::class);

Route::prefix('otherPapers')->group(function() {
    Route::get('manage', 'OtherPapersController@manage');
    Route::get('datatable', 'OtherPapersController@datatable');
});
Route::resource('otherPapers', OtherPapersController::class);
