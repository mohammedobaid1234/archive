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
Route::prefix('users')->group(function() {
    Route::get('manage', 'UsersController@manage');
    Route::get('datatable', 'UsersController@datatable');
    Route::get('/', 'UsersController@index');
    Route::prefix('roles')->group(function () {
        Route::get('manage', 'RolesController@manage');
        Route::get('datatable', 'RolesController@datatable');
        Route::get('employees', 'RolesController@employees_roles');
    });
    Route::resource('roles', RolesController::class);
    Route::prefix('permissions')->group(function () {
        Route::get('manage', 'PermissionsController@manage');
        Route::get('datatable', 'PermissionsController@datatable');
    });
    Route::resource('permissions', PermissionsController::class);
    

});

});

  
