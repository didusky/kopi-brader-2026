<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'table_id',
        'total_price',
        'payment_method',
        'status',
        'payment_status',
        'notes',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}