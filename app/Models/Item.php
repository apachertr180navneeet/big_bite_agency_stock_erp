<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Item extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'company_id', 'variation_id', 'name', 'description', 'tax_id', 'hsn_hac','opening_stock' // Add all the attributes you want to be mass assignable
    ];

    // Define the relationship with the Tax model
    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }

    // Define the relationship
    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }

    public function purchesBookItems()
    {
        return $this->hasMany(PurchesBookItem::class);
    }

    public function salesBookItems()
    {
        return $this->hasMany(SalesBookItem::class);
    }

    public function stockReports()
    {
        return $this->hasMany(StockReport::class);
    }
}
