<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sleep_time',
        'wake_time',
        'breakfast_time',
        'lunch_time',
        'dinner_time',
        'study_time_start',
        'study_time_end',
    ];

    protected $casts = [
        'sleep_time' => 'datetime:H:i',
        'wake_time' => 'datetime:H:i',
        'breakfast_time' => 'datetime:H:i',
        'lunch_time' => 'datetime:H:i',
        'dinner_time' => 'datetime:H:i',
        'study_time_start' => 'datetime:H:i',
        'study_time_end' => 'datetime:H:i',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
