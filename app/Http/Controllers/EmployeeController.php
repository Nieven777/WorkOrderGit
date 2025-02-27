<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response; // Make sure this line is present

class EmployeeController extends Controller
{
    public function index(): Response // This ensures type hinting works
    {
        return Inertia::render('EmployeeDashboard'); // This points to your Vue component
    }

    public function requestWork(): Response
    {
        return Inertia::render('employee/EmployeeRequestWork'); 
    }
}

