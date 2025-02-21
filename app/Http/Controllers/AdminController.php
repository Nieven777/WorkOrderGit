<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('AdminDashboard'); 
    }

    public function show(): Response 
    {
        return Inertia::render('admin/adminuserlist');
        return Inertia::render('admin/AdminEquipmentList');

    }
}
