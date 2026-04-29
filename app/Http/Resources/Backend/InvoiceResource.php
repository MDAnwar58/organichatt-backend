<?php

namespace App\Http\Resources\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'tran_id' => $this->tran_id,
            'total_amount' => $this->total,
            'order_status' => $this->order_status,
            'payment_method' => $this->payment_method,
            'paid_date' => $this->paid_date,
            'items' => OrderItemsResource::collection($this->whenLoaded('orderItems')),
            'user' => [
                'id' => $this->user?->id,
                'phone_number' => $this->user?->phone_number,
                'avatar' => $this->user?->avatar,
                'shippingInfo' => [
                    's_name' => $this->user?->shippingInfo?->name,
                    's_email' => $this->user?->shippingInfo?->email,
                    's_phone' => $this->user?->shippingInfo?->phone,
                    'city_or_town' => $this->user?->shippingInfo?->city_or_town,
                    'zip_code' => $this->user?->shippingInfo?->zip_code,
                    'house_no' => $this->user?->shippingInfo?->house_no,
                    'present_address' => $this->user?->shippingInfo?->present_address,
                    'address' => $this->user?->shippingInfo?->address,
                ]
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
