<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class OrderResource extends JsonResource
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
            'user_id' => $this->user_id,
            'tran_id' => $this->tran_id,
            'total_amount' => $this->total,
            'order_status' => $this->order_status,
            'payment_method' => $this->payment_method,
            'paid_date' => Carbon::parse($this->paid_date)->format('jS F Y') ?? null,
            'created_at' => Carbon::parse($this->created_at)->format('jS F Y') ?? null,
            'items' => OrderItemsResource::collection($this->whenLoaded('orderItems')),
        ];
    }
}
