<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'language'];

    protected $hidden = ['password', 'remember_token'];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function taskHistories(): HasMany
    {
        return $this->hasMany(TaskHistory::class);
    }

    public function preference()
    {
        return $this->hasOne(UserPreference::class);
    }
}
