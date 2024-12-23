<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'login' => $this->login,
            'email' => $this->email,
            'image' => $this->image ,
            'about' => $this->about,
            'birthday' => $this->birthday,
            'telegram' => $this->telegram,
            'is_finished' => $this->is_finished,
            'type' => $this->type,
            'github' => $this->github,
            'city' => $this->city,
            'phone' => $this->phone,
            'password' => $this->password,
            'worker_id' => $this->worker_id,
        ];
    }
}