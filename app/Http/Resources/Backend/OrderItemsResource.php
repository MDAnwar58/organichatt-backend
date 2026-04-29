<?php

namespace App\Http\Resources\Backend;

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
        return [
            'id' => $this->id,
            'qty' => $this->qty,
            'sale_price' => $this->sale_price,
            'sale_discount_price' => $this->sale_discount_price,
            'weight_price' => $this->weight_price,
            'weight_discount_price' => $this->weight_discount_price,
            'size_price' => $this->size_price,
            'size_discount_price' => $this->size_discount_price,
            'size_number_price' => $this->size_number_price,
            'size_number_discount_price' => $this->size_number_discount_price,
            'product' => [
                'id' => $this->product?->id,
                'name' => $this->product?->name,
                'brand' => $this->product?->brand?->name,
                'category' => $this->product?->category?->name,
                'sub_category' => $this->product?->sub_category?->name,
                'discount_price' => $this->product?->discount_price,
                'image_url' => $this->product?->image_url,
                'refoundable' => $this->product?->refundable,
                'rating' => $this->reviews?->avg('rating') ?? 0,
                'weights' => $this->product?->product_weights?->select('id'),
                'sizes' => $this->product?->product_sizes?->select('id'),
                'number_sizes' => $this->product?->product_size_numbers?->select('id'),
            ],
            'weight_id' => $this->weight_id,
            'size_id' => $this->size_id,
            'size_number_id' => $this->size_number_id,
        ];
    }
}
