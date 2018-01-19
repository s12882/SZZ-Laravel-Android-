<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{

   use HasApiTokens, Notifiable;
   use HasRoles;
    protected $fillable = [
        'login',
        'first_name',
        'surname',
        'email',
        'phoneNumber',
        'password',
        'is_active',
        'section_id',
        'mobile_pin',
        'device_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
      'created_at',
      'updated_at'
    ];

    protected $casts = [
      'login' => 'string',
      'first_name' => 'string',
      'surname' => 'string',
      'email' => 'string',
      'phoneNumber' => 'string',
      'password' => 'string',
      'section_id' => 'integer',
      'mobile_pin' => 'string',
      'device_id'=> 'string',
      'created_by' => 'integer',
      'updated_by' => 'integer',
    ];

    public function section(){
      return $this->belongsTo('App\Models\Section');
    }

    public function fullName(){
      return $this->first_name . " " . $this->surname;
    }

    public function tasks(){
      return $this->belongsToMany('App\Models\Task');
    }
}
