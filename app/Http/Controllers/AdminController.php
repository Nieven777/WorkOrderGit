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
        //ADMIN Navigation Routes
        return Inertia::render('admin/adminuserlist');
        

    }
    public function equipmentlist(): Response
    {
        return Inertia::render('admin/AdminEquipmentList');
    }
//     public function adminUserList(): Response
// {
//     return Inertia::render('admin/AdminUserList');
// }

// public function adminEquipmentList(): Response
// {
//     return Inertia::render('admin/AdminEquipmentList');
// }
    public function adworkorderlist(): Response
    {
        return Inertia::render('admin/AdminWorkOrderList');     
    }

}
