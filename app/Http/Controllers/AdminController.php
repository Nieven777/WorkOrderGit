<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response; // Make sure this line is present

class AdminController extends Controller
{
    public function index(): Response // This ensures type hinting works
    {
        return Inertia::render('AdminDashboard'); // This points to your Vue component
    }
}
