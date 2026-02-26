<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'category', 'price', 'description', 'is_ready', 'image'];
    protected $casts = ['is_ready' => 'boolean'];

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }
}