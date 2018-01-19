<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
 
    protected $fillable = [
        'name',
        'type',
        'amount',
        'ean',
        'category_id'
    ];

    protected $casts = [
        'name' => 'string',
        'type' => 'string',
        'amount' => 'float',
        'ean' => 'string',
        'category_id' => 'integer'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function category(){
        return $this->belongsTo('App\Models\ItemCategory');
    }

    public function warehouse_documents(){
        return $this->belongsToMany('App\Models\WarehouseDocument')->withPivot(['amount']);;
    }
}