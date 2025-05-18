<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type_task_id',
        'start_date',
        'end_date',
        'related_task_id',
        'completed'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TaskType::class, 'type_task_id');
    }

    public function relatedTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'related_task_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_task_user', 'group_task_id', 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'group_task_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(TaskHistory::class);
    }
}
