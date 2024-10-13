<?php

namespace App\DTOs;

use Carbon\Carbon;

class CloudEventDTO
{
    public function __construct(
        public string $specversion,
        public string $type,
        public string $source,
        public ?string $subject = null,
        public string $id,
        public ?string $time = null,
        public ?string $datacontenttype = null,
        public $data
    ) {}

    public static function handle(EventEmitterDTO $dto): self
    {
        return new self(
            specversion: "1.0",
            id: $dto->id ?? time() . '-' .uniqid(),
            subject: $dto->subject,
            type: $dto->type,
            source: request()->url(),
            time: Carbon::now()->toDateTimeString(),
            datacontenttype: is_string($dto->data) ? 'text/plain' : 'application/json',
            data: $dto->data
        );
    }

    /**
     * Convert the object into Array
     *
     * @return array
     */
    public function toArray(): array
    {
        return (array) $this;
    }
}
