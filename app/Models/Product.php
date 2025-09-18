<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'image',
        'description',
        'mrp',
        'price',
        'stock',
        'status',
        'user_id',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
