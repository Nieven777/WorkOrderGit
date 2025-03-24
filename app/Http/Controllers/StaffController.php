<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response; // Make sure this line is present

class StaffController extends Controller
{ 
    public function index(): Response // This ensures type hinting works
    {
        return Inertia::render('StaffDashboard'); // This points to your Vue component
    }

    public function staffworkorderlist(): Response
    {
        return Inertia::render('staff/StaffWorkOrderList'); // This points to your Vue component
    }
     // This method returns the page that lists work orders with status "Received"
     public function staffReceivedWorkOrderList()
     {
         return Inertia::render('staff/StaffReceivedWorkOrders');
     }
 
     // This method returns the page that lists work orders with status "Completed"
     public function staffCompletedWorkOrderList()
     {
         return Inertia::render('staff/StaffCompletedWorkOrdersTable');
     }
}
