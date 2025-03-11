<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchesReturnBook extends Model
{
    use HasFactory;


    protected $table = 'purches_return_books';

    protected $fillable = [
        'purches_book_id', 'return_invoice_number', 'vendor_id',
        'total_return_amount', 'discount', 'round_off', 'final_return_amount',
        'status', 'payment_type'
    ];

    public function purchesBook()
    {
        return $this->belongsTo(PurchesBook::class, 'purches_book_id');
    }

    public function returnItems()
    {
        return $this->hasMany(PurchesReturnBookItem::class, 'purches_return_book_id');
    }
}

