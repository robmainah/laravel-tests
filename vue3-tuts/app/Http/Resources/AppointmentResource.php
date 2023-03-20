<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            'client' => $this->client->full_name,
            'status' => [
                'name' => $this->status->name,
                'color' => $this->status->color(),
            ],
            'start_time' => $this->start_time->format('Y-m-d H:i A'),
            'end_time' => $this-> end_time->format('Y-m-d H:i A'),
        ];
    }
}
