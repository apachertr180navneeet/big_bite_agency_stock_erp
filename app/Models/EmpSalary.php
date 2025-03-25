<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class EmpSalary extends Model
{
    use HasFactory , SoftDeletes;

    protected $table = 'emp_salary';


    protected $fillable = [
        'user_id', 'total_working_day', 'total_present_day', 'diduction_amount', 'amount', 'slarly_mounth' // Add all the attributes you want to be mass assignable
    ];
}
