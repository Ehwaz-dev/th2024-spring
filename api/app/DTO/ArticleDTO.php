<?php

namespace App\DTO;

class ArticleDTO
{
    public function __construct(
        public ?int $userId,
        public string $title,
        public string $description,
    )
    {
    }
}
