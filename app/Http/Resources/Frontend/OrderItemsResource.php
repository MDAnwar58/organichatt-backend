<?php

namespace App\Http\Resources\Frontend;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $reviewCount = Review::where('invoice_id', $this->invoice_id)
            ->where('invoice_product_id', $this->id)
            ->where('user_id', $this->user_id)
            ->count();
        return [
            'id' => $this->id,
            'product' => [
                'id' => $this->product?->id,
                'name' => $this->product?->name,
                'category' => $this->product?->category?->name ?? null,
                'imageUrl' => $this->product?->image_url ?? null,
                'reviews_count' => $reviewCount ?? 0,
            ],
            'qty' => $this->qty ?? null,
            'sale_price' => $this->sale_price ?? null,
            'sale_discount_price' => $this->sale_discount_price ?? null,
            'weight_price' => $this->weight_price ?? null,
            'weight_discount_price' => $this->weight_discount_price ?? null,
            'size_price' => $this->size_price ?? null,
            'size_discount_price' => $this->size_discount_price ?? null,
            'size_number_price' => $this->size_number_price ?? null,
            'size_number_discount_price' => $this->size_number_discount_price ?? null,
            'weight' => [
                'number' => $this->weight?->number ?? null,
                'weight' => $this->weight?->weight ?? null,
            ],
            'size' => $this->size?->name ?? null,
            'size_number' => $this->size_number?->name ?? null,
            'product_optional_type' => $this->product_optional_type ?? null,
        ];
    }
}
