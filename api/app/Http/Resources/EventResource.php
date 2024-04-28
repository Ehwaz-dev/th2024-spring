<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->sqid,
            'name' => $this->name,
            'description' => $this->description,
            'dates' => [
                'start' => $this->start,
                'end' => $this->end,
            ],
            'owner' => [
                'id' => $this->owner->id,
                'name' => $this->owner->name,
            ],
            'maxUsers' => $this->max_users,
            'places' => $this->places,
            'tags' => $this->tags,
            'likes' => [
                'count' => $this->likes_count
            ],
            'comments' => [
                'count' => $this->comments_count,
                'list' => $this->comments,
            ]
        ];
    }
}
