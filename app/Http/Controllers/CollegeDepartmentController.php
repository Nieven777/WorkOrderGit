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

    public function getDepartments(): JsonResponse
    {
        return response()->json(CDepartment::select('department_id', 'department')->get());
    }
}
