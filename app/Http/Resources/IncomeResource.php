<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'description' => $this->description,
            'date' => $this->date->format('Y-m-d'),
            'location' => $this->location,
            'is_recurring' => $this->is_recurring,
            'category' => new CategoryResource($this->category),
            'payment_method' => new PaymentMethodResource($this->paymentMethod),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
