<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'tags' => $this->tags,
            'likes' => [
                'count' => $this->likes_count
            ],
            'comments' => [
                'count' => $this->comments_count,
                'list' => $this->comments
            ]
        ];
    }
}
