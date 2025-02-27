<?php

namespace App\Http\Controllers;

use App\Models\WConcern;
use Illuminate\Http\Request;

class ConcernController extends Controller
{
    public function index()
    {
        $concerns = WConcern::pluck('concern'); // Get only the 'concern' field
        return response()->json($concerns);
    }
}

