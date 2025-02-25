<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CDepartment extends Model
{
    use HasFactory;

    protected $table = 'c_departments'; // Ensure this matches your database table name
}
