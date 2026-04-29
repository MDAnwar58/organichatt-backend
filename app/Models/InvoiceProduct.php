<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'product_id',
        'user_id',
        'qty',
        'sale_price',
        'sale_discount_price',
        'product_optional_type',
        'product_optional',
        'weight_id',
        'weight_price',
        'weight_discount_price',
        'size_id',
        'size_price',
        'size_discount_price',
        'size_number_id',
        'size_number_price',
        'size_number_discount_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function weight()
    {
        return $this->belongsTo(Weight::class, 'weight_id');
    }
    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }
    public function size_number()
    {
        return $this->belongsTo(SizeNumber::class, 'size_number_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'invoice_product_id');
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
