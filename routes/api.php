<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\WorkPositionController;
use App\Http\Controllers\UserRoleController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register', [AuthController::class,'register']);
Route::post('auth/login', [AuthController::class,'login']);

Route::post('/auth/restore', [ResetPasswordController::class, 'forgetPassword']);
Route::post('/auth/restore/confirm', [ResetPasswordController::class, 'resetPassword']);

Route::apiResource('/users', UserController::class); // ->middleware('auth:api')
Route::apiResource('/user_roles', UserRoleController::class);

Route::get('/workers', [WorkerController::class, 'index']);
Route::post('/workers', [WorkerController::class, 'store']);
Route::get('/workers/{id}', [WorkerController::class, 'show']);
Route::put('/workers/{id}', [WorkerController::class, 'update']);
Route::delete('/workers/{id}', [WorkerController::class, 'destroy']);

Route::apiResource('/departments', DepartmentController::class);
Route::apiResource('/positions', WorkPositionController::class);