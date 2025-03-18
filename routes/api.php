<?php

use App\Http\Controllers\RequisitionerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CollegeDepartmentController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ConcernController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\DepartmentController;





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
Route::get('/users/{id}', [UserController::class, 'show']);


Route::get('/colleges', [CollegeDepartmentController::class, 'getColleges']);
Route::get('/departments', [CollegeDepartmentController::class, 'getDepartments']);

Route::post('/register', [RegisteredUserController::class, 'register']);


//Employee work order request
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json($request->user());
});

Route::get('/concerns', [ConcernController::class, 'index']); 
Route::get('/wrequisitioner', [RequisitionerController::class, 'index']);
Route::post('/submit-work-order', [WorkOrderController::class, 'store']);
Route::get('/my-work-orders', [WorkOrderController::class, 'show']);
Route::middleware('auth:sanctum')->get('/user-and-departments', [DepartmentController::class, 'getUserAndDepartments']);
Route::get('/colleges/{collegeId}/departments', [DepartmentController::class, 'getDepartmentsByCollege']);





// Admin routes for work orders (ensure your 'role:admin' middleware is set up correctly)
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Get all work orders for admin
    Route::get('/admin-work-orders', [WorkOrderController::class, 'index']);
    
    // Update a specific work order's status
    Route::patch('/work-orders/{id}', [WorkOrderController::class, 'update']);

    Route::get('/admin-work-order-counts', [WorkOrderController::class, 'getWorkOrderCounts']);

    // Endpoint for fetching today's work orders
    Route::get('/todays-work-orders', [WorkOrderController::class, 'getTodaysWorkOrders']);
});