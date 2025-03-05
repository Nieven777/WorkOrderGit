<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $table = 'work_order'; // Explicitly set the table name

    protected $fillable = [
        'requested_by',
        'requisitioner_type',
        'other_type',
        'college',
        'department',
        'concern',
        'other_concern',
        'date_requested',
        'description',
        'user_id',
        'ticket_number',
        'status',
    ];
}
