<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
    ];

    public function scopeIdOrSlug(Builder $query, string $slug): void
    {
        $query->where('id', $slug)->orWhere('slug', $slug);
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }
    public function likes()
    {
        return $this->morphMany(Like::class, 'liked');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commented')->orderBy('created_at', 'desc');
    }
}
