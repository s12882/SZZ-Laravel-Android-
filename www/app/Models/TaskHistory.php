<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskHistory extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'status',
        'users',
        'previous_version', 
        'items'
    ];

    protected $casts = [
        'task_id' => 'integer',
        'user_id' => 'integer',
        'status_id' => 'integer',
        'users' => 'longText', 
        'items' => 'longText'
    ];

    protected $dates = [
        'created_at'
    ];
}
