<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|string',
            'department' => 'required|string',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        // Handle file upload
        $profilePicturePath = $request->file('profile_picture') 
            ? $request->file('profile_picture')->store('profiles', 'public')
            : null;

        // Create user
        $user = User::create([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'department' => $request->department,
            'profile_picture' => $profilePicturePath,
        ]);

        return response()->json(['message' => 'User registered successfully!'], 201);
    }

    public function index()
    {
        $users = User::select(
            'id', 
            'username',
            'first_name', 
            'last_name', 
            'email', 
            'role', 
            'department', 
            'status'
        )->get();

        return response()->json($users);
    }

    public function toggleStatus($id)
{
    $user = User::findOrFail($id);
    $user->status = $user->status === 'Active' ? 'Inactive' : 'Active';
    $user->save();

    return response()->json(['message' => 'User status updated successfully!']);
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'username' => 'required|string|max:255|unique:users,username,' . $id,
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'profile_picture' => 'nullable|image|max:2048',
    ]);

    if ($request->file('profile_picture')) {
        $profilePicturePath = $request->file('profile_picture')->store('profiles', 'public');
        $user->profile_picture = $profilePicturePath;
    }

    $user->update($request->except(['password', 'profile_picture']));

    return response()->json(['message' => 'User updated successfully!']);
}


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
