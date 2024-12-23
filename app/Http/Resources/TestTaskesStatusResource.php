<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestTaskesStatusResource extends JsonResource
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
            'status' => $this->status,
            'end_date' => $this->end_date,
            'start_date' => $this->start_date,
            'user_name' => $this->user->name,
            'test_task_name' => $this->testTask->name,
            
        ];
    }
}
