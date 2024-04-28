<?php

namespace App\Models;

use Elastic\ScoutDriverPlus\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use Searchable;
    use HasFactory, HasSqid;

    const STATUS_CREATED = 'STATUS_CREATED';
    const STATUS_DONE = 'STATUS_DONE';
    const STATUS_CANCEL = 'STATUS_CANCEL';

    protected $casts = [
        'places' => 'array'
    ];

    protected $fillable = [
        'owner_id',
        'status',
        'request_to_join',
        'name',
        'description',
        'max_users',
        'start',
        'end',
        'places',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'sqid';
    }

    public function toSearchableArray()
    {
        $cities = $this->cities()->select(['region', 'city'])->get();
        $placeSet = $cities->map(fn($c) => $c->region)->merge($cities->map(fn($c) => $c->city)->toArray())->unique()->values();

        return [
            'name' => $this->name,
            'tags' => $this->tags()->get()->map(fn($t) => $t->tag),
            'place' => $placeSet,
            'start' => $this->start,
            'end' => $this->end,
        ];
    }



    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commented')->orderBy('created_at', 'desc');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'liked');
    }

    public function cities()
    {
        return $this->morphToMany(City::class, 'rc_event', 'event_id', 'rc_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_event')->wherePivot('accepted', true);
    }

    public function requestedUsers()
    {
        return $this->belongsToMany(User::class, 'user_event')->wherePivot('accepted', false);
    }


}
