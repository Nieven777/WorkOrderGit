<?php

namespace App\Http\Controllers;

use App\Models\CDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    public function getDepartmentsByCollege($collegeId)
    {
        $departments = CDepartment::where('college_id', $collegeId)->get();
        return response()->json($departments);
    }
    

    public function getUserAndDepartments()
{
    $user = auth()->user();

    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    // Fetch the departments based on the user's college code
    $departments = CDepartment::where('college', $user->college)->get();

    return response()->json([
        'user' => $user,
        'departments' => $departments
    ]);
}

}
