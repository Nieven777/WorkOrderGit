<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\CDepartment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CollegeDepartmentController extends Controller
{
    public function getColleges(): JsonResponse
    {
        return response()->json(College::select('c_u_id', 'college_unit')->get());
    }

    public function getDepartments(Request $request): JsonResponse
    {
        $collegeCode = $request->input('college_code');
    
        if ($collegeCode) {
            $departments = CDepartment::where('college_code', $collegeCode)
                                      ->select('department_id', 'department')
                                      ->get();
        } else {
            $departments = CDepartment::select('department_id', 'department')->get();
        }
    
        return response()->json($departments);
    }

    public function getDepartmentsByCollege($collegeId): JsonResponse
{
    $departments = CDepartment::where('college_id', $collegeId)
                              ->select('department_id', 'department')
                              ->get();

    return response()->json($departments);
}
public function getCollegeUnit()
{
    $colleges = College::all(); // Fetch all colleges
    return response()->json($colleges);
}

    

    
}
