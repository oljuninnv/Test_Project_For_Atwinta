<?php

namespace App\DTO;

class SendFileDTO
{
    public string $email;
    public string $url;
    public string $title;
    public string $body;

    public function __construct(string $email, string $url, string $title, string $body)
    {
        $this->email = $email;
        $this->url = $url;
        $this->title = $title;
        $this->body = $body;
    }
}