<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Company extends Model
{
    use HasFactory , SoftDeletes;

    protected $table = 'companies';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'address', 'city', 'type', 'status', 'gstin', 'short_code', 'state', 'zipcode', 'logo' // Add all the attributes you want to be mass assignable
    ];
}
