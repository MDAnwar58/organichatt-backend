<?php

namespace App\Http\Resources\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminReviewResource extends JsonResource
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
            'product' => [
                'id' => $this->product?->id,
                'name' => $this->product?->name,
                'image_url' => $this->product?->image_url ?? null,
            ],
            'rating' => $this->rating,
            'content' => $this->content,
            'created_at' => $this->created_at->format('j M, Y'),
        ];
    }
}
