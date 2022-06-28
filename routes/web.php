<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MailController;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

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


require __DIR__.'/auth.php';
Route::prefix('admin')
->as('admin.')
->group(function(){
    // dd(\Auth::user());
});
Route::get('/', [AuthenticatedSessionController::class, 'create']);
Route::get('/dashboard', [DashboardController::class,'index'])->middleware(['auth'])->name('dashboard');
require __DIR__.'/auth.php';
