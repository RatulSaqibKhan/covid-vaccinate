<?php

namespace App\DTOs;

class EventEmitterDTO extends AbstractDTO
{
    public function __construct(
        public string $type,
        public ?string $subject,
        public $data,
        public ?string $id = null
    ) {}
}
