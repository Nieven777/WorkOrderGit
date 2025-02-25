<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CollegeDepartmentController;
use App\Http\Controllers\Auth\RegisteredUserController;




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

Route::middleware('auth:sanctum')->get('/adminusertable', function (Request $request) {
    return $request->user();
});
Route::put('/users/{id}', [UserController::class, 'update']);
Route::put('/users/{id}/toggle-status', [UserController::class, 'toggleStatus']);

Route::get('/colleges', [CollegeDepartmentController::class, 'getColleges']);
Route::get('/departments', [CollegeDepartmentController::class, 'getDepartments']);

Route::post('/register', [RegisteredUserController::class, 'register']);
