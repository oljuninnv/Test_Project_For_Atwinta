<?php

namespace App\DTO;

class ErrorDTO
{
    public string $email;
    public string $title;
    public string $body;

    public function __construct(string $email,  string $title, string $body)
    {
        $this->email = $email;
        $this->title = $title;
        $this->body = $body;
    }
}