<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseDocument extends Model
{
    protected $fillable = [
        'warehouse_document_category',
        'created_by',
        'task_id'
    ];

    protected $casts = [
        'name' => 'string',
        'created_by' => 'integer'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function items(){
        return $this->belongsToMany('\App\Models\Item')->withPivot(['amount']);
    }

    public function author(){
        return $this->belongsTo('\App\Models\User', 'created_by');
    }
    public function task(){
        return $this->belongsTo('\App\Models\Task');
    }
}
