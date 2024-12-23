<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'adopted_at' => $this->adopted_at,
            'user' => new UserResource($this->user),
            'position' => new PositionResource($this->position),
            'department' => new DepartmentResource($this->department),
        ];
    }
}
