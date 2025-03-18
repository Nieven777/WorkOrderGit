<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CDepartment extends Model
{
    use HasFactory;

    protected $table = 'c_departments'; // Make sure this matches the table name
    protected $primaryKey = 'department_id';
    public $timestamps = false;

    protected $fillable = [
        'department',
        'college_id',
        'college_code',
        'department_c_u_code',
        'created_on'
    ];

    // Relationship to College model
    public function college()
    {
        return $this->belongsTo(College::class, 'college_id', 'c_u_id');
    }
}
