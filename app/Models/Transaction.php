<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_no',
        'price',
        'quantity',
        'payment_amount',
        'product_id',
    ];

    public function transactions(){
        return $this->belongsTo(Product::class);
    }
}
