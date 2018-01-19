<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Enums\WarehouseDocumentCategory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = [
        'name',
        'description',
        'location',
        'section_id',
        'status',
        'scheduled_for',
        'finished_at',
        'previous_task'
    ];

    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'location' => 'string',
        'section_id' => 'integer'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];
    public function section(){
        return $this->belongsTo('App\Models\Section');
    }

    public function users(){
        return $this->belongsToMany('App\Models\User');
    }

    public function comments(){
        return $this->hasMany('App\Models\Comments');
    }

    public function history(){
        return $this->hasMany('App\Models\TaskHistory');
    }

    public function documents(){
        return $this->hasMany('\App\Models\WarehouseDocument');
    }

    public function getStatusStringAttribute(){
        return TaskStatus::getDescription($this->status);
    }

    public function images() {
        return $this->hasMany('App\Models\TaskImage');
    }
    
    public function getItems(){
        $items = $this->documents->map(function($document){
            $category = $document->warehouse_document_category;
                return $document->items->map(function($item) use ($category){
                    if($category == WarehouseDocumentCategory::Pobranie || $category == WarehouseDocumentCategory::Likwidacja)
                        $amount = $item->pivot->amount;
                    else
                        $amount = -$item->pivot->amount;

                    return ['id' => $item->id, 'name' => $item->name,'type' => $item->type, 'amount' => $amount];
                });
        })->flatten(1)->groupBy('id');

        return $items->map(function($group){
            $first = $group->first();
            $amount = $group->sum('amount');
            if($amount != 0)
                return ['id' => $first['id'], 'name' => $first['name'] ,'type' => $first['type'], 'amount' => $amount];
        });
    }
    public function getCreatedAtAttribute($date)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');
    }
}