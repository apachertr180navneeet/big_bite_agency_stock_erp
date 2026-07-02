<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Variation extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'name', 'code', 'company_id' // Add all the attributes you want to be mass assignable
    ];


}
