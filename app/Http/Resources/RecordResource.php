<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecordResource extends JsonResource
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
            'seat' => $this->seat,
            'member_ID' => $this->member_ID,
            'member_amount' => (float) $this->member_amount,
            'order' => $this->order,
            'order_amount' => (float) $this->order_amount,
            'total' => (float) $this->total,
            'paid' => (bool) $this->paid,
            'online' => (bool) $this->online,
            'debt' => $this->debt ? (float) $this->debt : 0,
            'created_date' => $this->created_date?->format('Y-m-d H:i:s'),
            'modified_date' => $this->modified_date?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
