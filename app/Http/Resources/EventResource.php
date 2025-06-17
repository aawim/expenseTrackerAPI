<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'id'         => $this->id,
            // 'title'      => $this->title,
            // 'detail'     => $this->detail,
            // 'image'     => asset('/storage/event-images/'.$this->image),
            // 'user_name'  => $this->user->name, // Only include name
            // 'from_date' => $this->from_date->format('d:m:Y H:i'),
            // 'to_date'   => $this->to_date->format('d:m:Y H:i'),
            // 'created_at' => $this->created_at->format('d/m/Y'),
            'id'           => $this->id,
            'title'        => $this->title,
            'detail'       => $this->detail,
            'slots'        => $this->slots,
            'slots_booked' => $this->slots_booked,
            'image'        => $this->image,
            'status'       => $this->status,
            'availability'  => $this->availability,
            'vanue'        => $this->vanue,
            'from_date'    => $this->from_date ? $this->from_date->format('d:m:Y H:i') : null,
            'to_date'      => $this->to_date ? $this->to_date->format('d:m:Y H:i') : null,
            'user_name'    => $this->user->name,

        ];
    }
}
