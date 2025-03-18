<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    use HasFactory;

    protected $table = 'colleges'; // Make sure this matches the table name
    protected $primaryKey = 'c_u_id'; // Set primary key
    public $timestamps = false; // Disable timestamps if they are not used

    protected $fillable = [
        'college_unit', 
        'college_unit_code', 
        'dep_chair', 
        'dep_chair_id', 
        'created_on', 
        'recieve_concern'
    ];

    // Relationship to Department model
    public function departments()
    {
        return $this->hasMany(CDepartment::class, 'college_id', 'c_u_id');
    }
}
