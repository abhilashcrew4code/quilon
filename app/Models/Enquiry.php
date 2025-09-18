<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable =  [
        'customer_name',
        'product_id',
        'quantity',
        'date',
        'remarks',
        'status',
        'order_status',
        'user_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
