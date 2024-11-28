<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'github_id', 'avatar'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function snippets()
    {
        return $this->hasMany(Snippet::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }
}
