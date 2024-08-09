<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $table = 'order_items';
    protected $fillable = [
        'user_id',
        'cart_id',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }
}
