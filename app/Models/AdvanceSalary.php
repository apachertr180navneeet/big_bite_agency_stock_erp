<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class AdvanceSalary extends Model
{
    use HasFactory , SoftDeletes;

    protected $table = 'advance_salary';


    protected $fillable = [
        'user_id', 'amount', 'date' // Add all the attributes you want to be mass assignable
    ];
}
