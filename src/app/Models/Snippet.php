<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Snippet extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'code',
        'language',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }
}
