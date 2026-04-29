<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tran_id',
        'total',
        'vat',
        'payable',
        'order_status',
        'is_read',
        'payment_method',
        'paid_date',
    ];

    public function orderItems()
    {
        return $this->hasMany(InvoiceProduct::class, 'invoice_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
