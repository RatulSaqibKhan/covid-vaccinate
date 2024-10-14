<?php

namespace App\DTOs;

class MailDTO
{
    public string $name;
    public string $email;
    public string $subject;
    public string $message;

    public function __construct($name, $email, $subject, $message)
    {
        $this->name = $name;
        $this->email = $email;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Return the formatted mail data as an array.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message
        ];
    }
}
