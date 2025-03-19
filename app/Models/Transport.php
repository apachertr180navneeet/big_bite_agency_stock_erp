<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Transport extends Model
{
    use HasFactory , SoftDeletes;

    protected $table = 'transport';

    protected $fillable = [
        'name', 'status','company_id'
    ];


}
