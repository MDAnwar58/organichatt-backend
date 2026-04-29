<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewGetResource extends JsonResource
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
            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'avatar' => $this->user?->avatar ?? null,
            ],
            'invoice_product_id' => $this->invoice_product_id,
            'rating' => $this->rating,
            'content' => $this->content,
            'created_at' => $this->created_at->format('j M, Y'),
        ];
    }
}
