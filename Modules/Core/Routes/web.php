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

Route::middleware(['auth'])->group(function() {

    Route::get('currencies', 'CurrenciesController@index');

    Route::prefix('countries')->group(function() {
        Route::get('manage', 'CountriesController@manage');
        Route::get('datatable', 'CountriesController@datatable');

        Route::get('provinces', 'ProvincesController@index');

        Route::prefix('{country_id}')->group(function() {
            Route::prefix('provinces')->group(function() {
                Route::get('manage', 'ProvincesController@manage');
                Route::get('datatable', 'ProvincesController@datatable');
            });
            Route::resource('provinces', ProvincesController::class);
        });
    });
    Route::resource('countries', CountriesController::class);

    Route::prefix('banks')->group(function() {
        Route::get('manage', 'BanksController@manage');
        Route::get('datatable', 'BanksController@datatable');
    });
    Route::resource('banks', BanksController::class);

    Route::prefix('electricities')->group(function() {
        Route::get('manage', 'ElectricitiesController@manage');
        Route::get('datatable', 'ElectricitiesController@datatable');
        Route::get('latest/{type}', 'ElectricitiesController@latest');
    });
    Route::resource('electricities', ElectricitiesController::class);
});

