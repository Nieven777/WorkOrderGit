<?php


use Inertia\Inertia;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;



Route::get('/', function () {
    return Inertia::render('Auth/Login'); // This ensures Inertia renders the Login.vue component
})->name('login');

// Authentication routes (handled by Breeze)
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return inertia('Auth/Login');
    })->name('login');

    Route::get('/register', function () {
        return inertia('Auth/Register');
    })->name('register');
});

// Middleware for authenticated users
Route::middleware(['auth'])->group(function () {
    // Redirect user based on role
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        if ($role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($role === 'staff') {
            return redirect('/staff/dashboard');
        } else {
            return redirect('/employee/dashboard');
        }
    })->name('dashboard');

    // Admin routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    });

    // Staff routes
    Route::middleware(['role:staff'])->group(function () {
        Route::get('/staff/dashboard', [StaffController::class, 'index'])->name('staff.dashboard');
    });

    // Employee routes
    Route::middleware(['role:employee'])->group(function () {
        Route::get('/employee/dashboard', [EmployeeController::class, 'index'])->name('employee.dashboard');
    });

    // Logout route
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
Route::get('/home', function () {
    if (auth()->user()->role === 'admin') {
        return redirect('/admin/dashboard');
    } elseif (auth()->user()->role === 'staff') {
        return redirect('/staff/dashboard');
    } else {
        return redirect('/employee/dashboard');
    }
})->middleware(['auth']);


require __DIR__.'/auth.php';

