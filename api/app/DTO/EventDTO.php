<?php

namespace App\DTO;

use Illuminate\Support\Carbon;

class EventDTO
{
    public function __construct(

        public string $name,
        public string $description,
        public Carbon $start,
        public Carbon $end,
        public bool $requestToJoin,
        public string $status,
        public ?int $ownerId,
        public ?int $maxUsers,
        public array $places = [],
        public array $tagIds = [],
    )
    {
    }
}
