<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'total_amount',
        'date',
        'remarks',
        'status',
        'payment_status',
        'delivery_status',
        'user_id',
    ];

    // One order has many items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
