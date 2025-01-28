<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptBookVoucher extends Model
{
    use HasFactory;


    protected $table = 'receipt_book_vouchers';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date', 'receipt_vouchers_number', 'customer_id', 'company_id', 'amount', 'discount', 'round_off', 'grand_total' , 'status','remark','bank_id','payment_type' // Add all the attributes you want to be mass assignable
    ];
}
