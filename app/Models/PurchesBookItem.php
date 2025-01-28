<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchesBookItem extends Model
{
    use HasFactory;

    protected $table = 'purches_book_items';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'purches_book_id', 'item_id', 'quantity', 'rate', 'tax', 'amount', 'status','preturn' // Add all the attributes you want to be mass assignable
    ];


    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

}
