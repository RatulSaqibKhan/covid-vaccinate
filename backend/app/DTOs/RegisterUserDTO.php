<?php

namespace App\DTOs;

class RegisterUserDTO
{
    public string $name;
    public string $email;
    public string $nid;
    public ?string $phone;
    public int $vaccine_center_id;
    public string $registered_at;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->nid = $data['nid'];
        $this->phone = $data['phone'] ?? null;
        $this->vaccine_center_id = (int) $data['vaccine_center_id'];
        $this->registered_at = \now()->toDateString();
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'nid' => $this->nid,
            'phone' => $this->phone,
            'vaccine_center_id' => $this->vaccine_center_id,
            'registered_at' => $this->registered_at,
        ];
    }
}
