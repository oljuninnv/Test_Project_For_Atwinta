<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/reset/{token}', [ResetPasswordController::class,'verificationMail']);
Route::get('/auth/reset', [ResetPasswordController::class, 'resetPasswordLoad']);
Route::post('/auth/reset', [ResetPasswordController::class, 'resetPassword']);
