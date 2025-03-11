<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesBook extends Model
{
    use HasFactory;


    protected $table = 'sales_books';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date', 'dispatch_number', 'customer_id', 'transport', 'igst', 'sgst','cgst', 'other_expense', 'discount', 'round_off', 'grand_total' , 'status' , 'company_id', 'item_weight','amount_before_tax','recived_amount','balance_amount','transport','vehicle_no' ,'transport','vehicle_no' ,'sub_compnay_id','payment_type' ,'discount_value','cess','sales_return' // Add all the attributes you want to be mass assignable
    ];

    // Other model code...

    public function salesbookitem()
    {
        return $this->hasMany(SalesBookItem::class, 'sales_book_id');
    }
}
