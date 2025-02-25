<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class UserController extends Controller
{
    

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
            'user_id'
        )->get();

        return response()->json($users);
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
