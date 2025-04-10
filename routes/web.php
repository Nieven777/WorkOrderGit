<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CollegeDepartmentController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\WordExportController;
use App\Http\Controllers\WorkOrderController;

// Login page
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        if ($role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($role === 'staff') {
            return redirect('/staff/dashboard');
        } else {
            return redirect('/employee/dashboard');
        }
    }
    
    return Inertia::render('Auth/Login'); // Show login page if not authenticated
})->name('login');


// Guest routes (Login & Register)
Route::middleware('guest')->group(function () {
    Route::get('/login', fn () => Inertia::render('Auth/Login'))->name('login');
    
});

// Middleware for authenticated users
Route::middleware(['auth'])->group(function () {

    // Unified Role-Based Redirection
    Route::get('/dashboard', function () {
        return redirect(roleBasedRedirect(auth()->user()->role));
    })->name('dashboard'); 

    Route::get('/home', function () {
        return redirect(roleBasedRedirect(auth()->user()->role)); 
    });     

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        // Admin Navigation Routes
        Route::get('/adminuserlist', [AdminController::class, 'show'])->name('admin.adminuserlist');
        Route::get('/AdminEquipmentList', [AdminController::class, 'equipmentlist'])->name('admin.AdminEquipmentList');
        Route::get('/AdminWorkOrderList', [AdminController::class, 'adworkorderlist'])->name('admin.AdminWorkOrderList');
        Route::get('/AdminRequestWork', [AdminController::class, 'addworkorder'])->name('admin.AdminRequestWork');
   
        // Route::get('/AdminEquipmentList', [AdminController::class, 'adminEquipmentList']);
 
        //Admin Crud Routes
        Route::post('/register', [RegisteredUserController::class, 'register']);
        Route::get('/adminusertable', [UserController::class, 'index']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
        Route::get ('/collegeunit', [CollegeDepartmentController::class, 'getCollegeUnit']);


    });

// Staff routes for authenticated staff users
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffController::class, 'index'])->name('dashboard');
    Route::get('/StaffWorkOrderList', [StaffController::class, 'staffWorkOrderList'])->name('StaffWorkOrderList');
    Route::get('/StaffReceivedWorkOrders', [StaffController::class, 'staffReceivedWorkOrderList'])->name('StaffReceivedWorkOrders');
    Route::get('/StaffCompletedWorkOrders', [StaffController::class, 'staffCompletedWorkOrderList'])->name('StaffCompletedWorkOrders');
});

    // Employee Routes
    Route::middleware(['role:employee'])->prefix('employee')->name('employee.')->group(function () {
        Route::get('/dashboard', [EmployeeController::class, 'index'])->name('dashboard');
        Route::get('/EmployeeRequestWork', [EmployeeController::class, 'requestWork'])->name('employee.EmployeeRequestWork');
        Route::get('/MyWorkOrderRequests', [EmployeeController::class, 'myrequestWork'])->name('employee.MyWorkOrderRequests');
        Route::get('/colleges/{collegeCode}/departments', [CollegeDepartmentController::class, 'getDepartmentsByCollege']);


    });

//Print
Route::post('/export-word', [WordExportController::class, 'exportToWord']);


// Print as PDF (direct print)
Route::get('/work-orders/{id}/print-pdf', [WorkOrderController::class, 'printPDF'])->name('work-orders.print-pdf');

// Export as Word (download .docx)
Route::get('/work-orders/{id}/export-word', [WorkOrderController::class, 'exportWord'])->name('work-orders.export-word');

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

require __DIR__.'/auth.php';

// Helper function for role-based redirection
function roleBasedRedirect($role) {
    return match ($role) {
        'admin' => '/admin/dashboard',
        'staff' => '/staff/dashboard',
        default => '/employee/dashboard',
    };
}
