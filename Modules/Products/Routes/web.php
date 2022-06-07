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
    Route::prefix('products')->group(function() {
        Route::get('manage', 'ProductsController@manage');
        Route::get('datatable', 'ProductsController@datatable');
        Route::post('/{product_id}/status', 'ProductsController@status');
        Route::post('/{product_id}/approve', 'ProductsController@approve');
        Route::delete('/{product_id}', 'ProductsController@destroy');
        Route::post('/{product_id}/update', 'ProductsController@update');
        Route::post('/{product_id}/images', 'ProductImagesController@store');
        Route::delete('/{product_id}/images/{image_id}', 'ProductImagesController@destroy');
        
    });
    Route::resource('products', ProductsController::class);
    
    Route::prefix('categories')->group(function() {
        Route::get('manage', 'CategoriesController@manage');
        Route::get('datatable', 'CategoriesController@datatable');
        Route::get('create', 'CategoriesController@create');
        Route::post('/', 'CategoriesController@store');
        Route::get('/', 'CategoriesController@index');
        Route::get('/children', 'CategoriesController@children');
        
        Route::get('{category_id}', 'CategoriesController@show');
        Route::put('{category_id}', 'CategoriesController@update');
        Route::delete('{category_id}', 'CategoriesController@destroy');
    });
    Route::resource('categories', CategoriesController::class);
    
});

