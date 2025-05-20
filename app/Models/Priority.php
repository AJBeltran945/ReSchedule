<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Priority extends Model
{
    protected $fillable = ['name', 'color', 'importance'];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function taskTypes()
    {
        return $this->hasMany(TaskType::class);
    }
}
