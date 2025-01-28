<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesBookItem extends Model
{
    use HasFactory;

    protected $table = 'sales_book_items';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sales_book_id', 'item_id', 'quantity', 'rate', 'tax', 'amount', 'status', 'sreturn' // Add all the attributes you want to be mass assignable
    ];


    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
