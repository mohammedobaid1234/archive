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
Route::prefix('motors')->group(function() {
    Route::get('manage', 'MotorsController@manage');
    Route::get('datatable', 'MotorsController@datatable');
});
Route::resource('motors', MotorsController::class);

Route::prefix('machines')->group(function() {
    Route::get('manage', 'MachinesController@manage');
    Route::get('datatable', 'MachinesController@datatable');
});
Route::resource('machines', MachinesController::class);