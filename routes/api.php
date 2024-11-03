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
use App\Http\Controllers\Api\GetDepartmentInformationController;
use App\Http\Controllers\Api\GetWorkersInformationFromDepartmentsController;
use App\Http\Controllers\Api\GetUserInformationController;
use App\Http\Controllers\Api\GetDataForWorkerController;
use App\Http\Controllers\AdminController;
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

Route::apiResource('/users', UserController::class) ->middleware('auth:api');
Route::apiResource('/user_roles', UserRoleController::class);


// Контроллеры для работников
Route::get('/workers', [WorkerController::class, 'index']);
Route::post('/workers', [WorkerController::class, 'store']);
Route::get('/workers/{id}', [WorkerController::class, 'show']);
Route::put('/workers/{id}', [WorkerController::class, 'update']);
Route::delete('/workers/{id}', [WorkerController::class, 'destroy']);

//Контроллеры для отделов
Route::apiResource('/departments', DepartmentController::class);

// Контроллеры для позиций
Route::apiResource('/positions', WorkPositionController::class);

// Данные, которые мы получаем на страницах с контентом
Route::get('/departments_information', [GetDepartmentInformationController::class, 'index']);
Route::get('/workers_information/{id}', [GetWorkersInformationFromDepartmentsController::class, 'show']);
Route::get('/user/{worker_id}', [GetUserInformationController::class, 'getUserInfo']);
Route::get('/get_data_for_worker', [GetDataForWorkerController::class, 'get_informationWithUser']);
Route::get('/get_data_for_worker_without_user', [GetDataForWorkerController::class, 'get_informationWithoutUser']);
Route::get('/get_admins', [AdminController::class, 'get_admins']);
Route::get('/get_others', [AdminController::class, 'get_others']);
Route::delete('/delete_admin/{id}', [AdminController::class, 'delete_admin']);