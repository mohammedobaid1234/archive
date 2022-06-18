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
Route::prefix('employees')->group(function() {
    Route::get('manage', 'EmployeesController@manage');
    Route::get('datatable', 'EmployeesController@datatable');

    Route::get('change-password/create', 'ChangePasswordController@create');
    Route::post('change-password', 'ChangePasswordController@store');

    Route::prefix('{employment_id}')->group(function() {
        Route::post('reset-password', 'EmployeesController@reset_password');
    });
});
Route::resource('employees', EmployeesController::class);

Route::prefix('teams')->group(function() {
    Route::get('manage', 'TeamsController@manage');
    Route::get('datatable', 'TeamsController@datatable');
});
Route::resource('teams', TeamsController::class);
});

Route::prefix('departments')->group(function() {
    Route::get('manage', 'DepartmentsController@manage');
    Route::get('datatable', 'DepartmentsController@datatable');
});
Route::resource('departments', DepartmentsController::class);

Route::prefix('jawwalBills')->group(function() {
    Route::get('manage', 'JawwalBillsController@manage');
    Route::get('datatable', 'JawwalBillsController@datatable');
});
Route::resource('jawwalBills', JawwalBillsController::class);

Route::prefix('EmployeesAdvances')->group(function() {
    Route::get('manage', 'EmployeesAdvancesController@manage');
    Route::get('datatable', 'EmployeesAdvancesController@datatable');
});
Route::resource('EmployeesAdvances', EmployeesAdvancesController::class);



