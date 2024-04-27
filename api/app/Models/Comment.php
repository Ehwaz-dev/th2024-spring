<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'commented_id',
        'commented_type',
        'attachments',
    ];

    public function commented(): MorphTo
    {
        return $this->morphTo();
    }

}
