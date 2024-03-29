<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'price',
        'retail_price',
        'description',
        'category_id',
        'brand',
        'size',
        'rating_avg',
        'rating_count',
        'inventory_count',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
