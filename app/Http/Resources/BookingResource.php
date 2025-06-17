<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            // 'events' => $this->event,
            'id'      => $this->id,
            'user_id' => $this->user_id,
            'status'  => $this->status,
            'events'  => $this->event,

        ];
    }
}
