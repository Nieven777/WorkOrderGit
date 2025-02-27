<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisitioner extends Model
{
    use HasFactory;

    
    protected $table = 'w_requisitioner_type';

    protected $fillable = ['type']; 
}
