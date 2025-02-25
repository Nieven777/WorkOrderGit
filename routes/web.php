<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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
        Route::get('/AdminEquipmentList', [AdminController::class, 'show'])->name('admin.AdminEquipmentList');
        // Route::get('/adminuserlist', [AdminController::class, 'adminUserList']);
        // Route::get('/AdminEquipmentList', [AdminController::class, 'adminEquipmentList']);

        //Admin Crud Routes
        Route::post('/register', [RegisteredUserController::class, 'register']);
        Route::get('/adminusertable', [UserController::class, 'index']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);


    });

    // Staff Routes
    Route::middleware(['role:staff'])->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [StaffController::class, 'index'])->name('dashboard');
    });

    // Employee Routes
    Route::middleware(['role:employee'])->prefix('employee')->name('employee.')->group(function () {
        Route::get('/dashboard', [EmployeeController::class, 'index'])->name('dashboard');
    });

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
