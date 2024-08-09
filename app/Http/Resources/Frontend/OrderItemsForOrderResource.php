<?php

namespace App\Http\Resources\Frontend;

use App\Http\Resources\Frontend\Common\Cart\CartsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemsForOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'cart' => new CartsResource($this->whenLoaded('cart')),
        ];
    }
}
