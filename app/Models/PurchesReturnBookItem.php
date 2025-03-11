<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchesReturnBookItem extends Model
{
    use HasFactory;


    protected $table = 'purches_return_book_items';

    protected $fillable = [
        'purches_return_book_id', 'purches_book_item_id', 'category','returned_quantity', 'rate', 'tax', 'cess', 'total_return_amount',
        'status'
    ];

    public function purchesReturnBook()
    {
        return $this->belongsTo(PurchesReturnBook::class, 'purches_return_book_id');
    }
}

