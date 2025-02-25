<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
class RegisteredUserController extends Controller
{
    public function register(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users',
            'first_name' => 'required',
            'last_name' => 'required',
            'college' => 'required',
            'department' => 'required',
            'role' => 'required',
            'email' => 'required|email|unique:users',
            'user_id' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
            'profile_picture' => 'nullable|image|max:2048', // Max 2MB image
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Handle profile picture upload
        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profiles', 'public');
        }
    
        // Create user
        $user = User::create([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'college' => $request->college,
            'department' => $request->department,
            'role' => $request->role,
            'email' => $request->email,
            'user_id' => $request->user_id,
            'profile_picture' => $profilePicturePath,
            'password' => Hash::make($request->password),
        ]);
    
        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }
}
