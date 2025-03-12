<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    use HasFactory;

    protected $table = 'colleges'; // Your table name

    protected $fillable = [
        'college_unit_code',
        'college_name'
    ];

    /**
     * Relationship with Department
     */
    public function departments()
    {
        return $this->hasMany(CDepartment::class, 'college', 'college_unit_code');
    }
}
