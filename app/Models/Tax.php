<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Tax extends Model
{
    use HasFactory , SoftDeletes;


    protected $fillable = [
        'company_id', 'rate', 'name' // Add all the attributes you want to be mass assignable
    ];
}
