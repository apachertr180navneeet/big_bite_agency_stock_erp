<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class BankAndCash extends Model
{
    use HasFactory , SoftDeletes;


    protected $table = 'bank_and_cashes';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date', 'serial_no', 'amount', 'deposite_in', 'withdraw_in', 'description', 'status', 'company_id', 'particular' // Add all the attributes you want to be mass assignable
    ];
}
