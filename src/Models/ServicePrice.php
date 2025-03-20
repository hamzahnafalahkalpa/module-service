<?php

namespace Gii\ModuleService\Models;

use Gii\ModuleService\Resources\ServicePrice\ShowServicePrice;
use Gii\ModuleService\Resources\ServicePrice\ViewServicePrice;
use Illuminate\Database\Eloquent\SoftDeletes;
use Zahzah\LaravelHasProps\Concerns\HasProps;
use Zahzah\LaravelSupport\Models\BaseModel;

class ServicePrice extends BaseModel{
    use HasProps, SoftDeletes;
    protected $list               = ['id','parent_id','service_id','service_item_id','service_item_type','price','props'];
    protected $show               = [];
    // public array $current_conditions = [
    //     'service_id','service_item_id','service_item_type'        
    // ];

    protected static function booted(): void{
        parent::booted();
        static::creating(function($query){
            if (!isset($query->price)) $query->price = 0;
        });
        static::created(function($query){
            // static::withoutEvents(function() use ($query){
            //     $service = $query->service;
            //     if (isset($service)){
            //         $service->price += $query->price;
            //         $service->save();
            //     }
            // });
        });
        static::updated(function($query){
            if ($query->isDirty('deleted_at')){
                $query->childs()->delete();
            }
        });
    }

    public function toViewApi(){
        return new ViewServicePrice($this);
    }

    public function toShowApi(){
        return new ShowServicePrice($this);
    }

    //EIGER SECTION
    public function service(){return $this->belongsToModel('Service');}
    public function serviceItem(){return $this->morphTo();}
}
