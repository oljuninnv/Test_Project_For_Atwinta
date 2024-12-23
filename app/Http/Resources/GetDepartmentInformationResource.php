<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetDepartmentInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'department_id' => $this->id,
            'department_name' => $this->name,
            'employee_count' => $this->workers->count(),
            'positions' => $this->workers->map(function ($worker) {
                return [
                    'id' => $worker->position->id,
                    'name' => $worker->position->name,
                ];
            })->unique('id')->values()->toArray(), // Удаляем дубликаты по id
        ];
    }
}
