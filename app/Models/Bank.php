<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use HasFactory , SoftDeletes;


    protected $fillable = [
        'company_id', 'name', 'bank_name', 'account_number', 'ifsc_code', 'branch_name', 'status' , 'opening_blance', 'show_invoice'
    ];
}
