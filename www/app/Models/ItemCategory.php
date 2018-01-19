<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'name' => 'string',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function items(){
        return $this->hasMany('App\Models\Item');
    }
}
