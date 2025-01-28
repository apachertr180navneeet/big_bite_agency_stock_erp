<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentBook extends Model
{
    use HasFactory;

    protected $table = 'payment_books';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date', 'payment_vouchers_number', 'vendor_id', 'company_id', 'amount', 'discount', 'round_off', 'grand_total' , 'status','remark','bank_id','payment_type' // Add all the attributes you want to be mass assignable
    ];
}
