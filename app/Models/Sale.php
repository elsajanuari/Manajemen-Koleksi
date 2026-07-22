<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'invoice_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_price',
        'shipping_cost',
        'grand_total',
        'payment_status',
        'order_status',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
