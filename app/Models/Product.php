<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'product_code',
        'name',
        'slug',
        'category',
        'description',
        'price',
        'stock',
        'image',
        'status',
        'weight',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
