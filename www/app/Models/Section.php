<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
  protected $fillable = [
    'name',
    'left',
    'right',
    'parent_id'
  ];

  protected $casts = [
    'name' => 'string',
    'left' =>'integer',
    'right' =>'integer',
    'parent_id' =>'integer'
  ];

  public function users() {
        return $this->hasMany('App\Models\User');
    }
}
