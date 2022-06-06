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
    Route::prefix('customers')->group(function() {
        Route::get('manage', 'CustomersController@manage');
        Route::get('datatable', 'CustomersController@datatable');

        Route::prefix('{customer_id}')->group(function() {
            Route::post('reset-password', 'CustomersController@reset_password');
            Route::get('/profile', 'ProfileController@index');
            Route::get('/contracts/datatable', 'ContractsForCustomerController@datatable');
            Route::get('/products/datatable', 'ProductsForCustomerController@datatable');
        });
    });
    Route::resource('customers', CustomersController::class);
    Route::prefix('contracts')->group(function() {
        Route::get('manage', 'ContractsController@manage');
        Route::get('datatable', 'ContractsController@datatable');
    });
    Route::resource('contracts', ContractsController::class);
    
    Route::prefix('categories_of_contracts')->group(function() {
        Route::get('manage', 'CategoriesOfContractsController@manage');
        Route::get('datatable', 'CategoriesOfContractsController@datatable');
    });
    Route::resource('categories_of_contracts', CategoriesOfContractsController::class);

});
