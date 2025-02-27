<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WConcern extends Model
{
    use HasFactory;

    protected $table = 'w_concern';

    protected $fillable = ['concern']; 
}
