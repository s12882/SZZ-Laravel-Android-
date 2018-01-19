<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $fillable = [
        'content',
        'task_id',
        'author_id'
    ];

    protected $casts = [
        'content' => 'string',
        'task_id' => 'integer',
        'author_id' => 'integer'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function task(){
        return $this->belongsTo('App\Models\Task');
    }

    public function author(){
        return $this->belongsTo('App\Models\User');
    }
}