<?php

namespace App\Http\Controllers;
use App\Models\Requisitioner;

use Illuminate\Http\Request;

class RequisitionerController extends Controller
{
    public function index(){
        $wrequisitioner = Requisitioner::pluck('type'); // Get only the 'concern' field
        return response()->json($wrequisitioner);
    }
}
