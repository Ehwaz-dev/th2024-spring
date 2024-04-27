<?php

namespace App\DTO;

class CommentDTO
{
    public function __construct(
        public int $userId,
        public string $comment
    )
    {
    }
}
