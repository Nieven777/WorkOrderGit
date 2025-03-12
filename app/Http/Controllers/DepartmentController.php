<?php

namespace App\Http\Controllers;

use App\Models\CDepartment;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function getDepartmentsByCollege(Request $request)
    {
        $collegeUnitCode = $request->input('college_unit_code');
        
        $departments = CDepartment::where('college', $collegeUnitCode)->get();
        
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
