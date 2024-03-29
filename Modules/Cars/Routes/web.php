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

Route::prefix('cars')->group(function() {
    Route::get('manage', 'CarsController@manage');
    Route::get('datatable', 'CarsController@datatable');
});
Route::resource('cars', CarsController::class);

Route::prefix('carsPapers')->group(function() {
    Route::get('manage', 'CarsPapersController@manage');
    Route::get('datatable', 'CarsPapersController@datatable');
});
Route::resource('carsPapers', CarsPapersController::class);

Route::prefix('carsMaintenances')->group(function() {
    Route::get('manage', 'CarsMaintenancesController@manage');
    Route::get('datatable', 'CarsMaintenancesController@datatable');
});
Route::resource('carsMaintenances', CarsMaintenancesController::class);

Route::prefix('carConsumptions')->group(function() {
    Route::get('manage', 'CarsConsumptionController@manage');
    Route::get('datatable', 'CarsConsumptionController@datatable');
});
Route::resource('carConsumptions', CarsConsumptionController::class);
