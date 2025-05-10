<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskType extends Model
{
    protected $fillable = ['name', 'description'];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'type_task_id');
    }
}
