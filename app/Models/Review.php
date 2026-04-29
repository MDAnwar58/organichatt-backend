<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'invoice_id',
        'invoice_product_id',
        'product_id',
        'rating',
        'content',
        'is_read',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function invoiceProduct()
    {
        return $this->belongsTo(InvoiceProduct::class);
    }
}
