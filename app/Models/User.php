<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;



class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username', 'first_name', 'middle_name', 'last_name', 'college', 'department', 'role', 
        'email', 'user_id', 'profile_picture', 'password'
    ];
    


    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isEmployee()
    {
        return $this->role === 'employee';
    }
}

