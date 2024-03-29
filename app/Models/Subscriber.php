<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'product_id'];

    // Define any relationships here
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
