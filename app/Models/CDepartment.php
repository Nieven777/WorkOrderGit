<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CDepartment extends Model
{
    use HasFactory;

    protected $table = 'c_departments'; // Your table name

    protected $fillable = [
        'department', 
        'college'
    ];

    /**
     * Relationship with College
     */
    public function college()
    {
        return $this->belongsTo(College::class, 'college', 'college_unit_code');
    }
}
