<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const STATUSES = [
        'pending' => 1,
        'processing' => 2,
        'completed' => 3,
        'cancelled' => 4,
    ];

    protected $fillable = [
        'order_status_id',
        'total_price',
        'total_amount',
        'products'
    ];

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')
            ->withPivot('quantity');
    }
}
