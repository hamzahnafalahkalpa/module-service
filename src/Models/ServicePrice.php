<?php

namespace Hanafalah\ModuleService\Models;

use Hanafalah\ModuleService\Resources\ServicePrice\ShowServicePrice;
use Hanafalah\ModuleService\Resources\ServicePrice\ViewServicePrice;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class ServicePrice extends BaseModel
{
    use HasUlids, HasProps, SoftDeletes;
    
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    protected $list = [
        'id', 'parent_id', 'service_id', 'service_item_id', 'service_item_type', 
        'price', 'cogs', 'margin' , 'tax', 'props'
    ];
    protected $show = [];

    protected static function booted(): void
    {
        parent::booted();
        static::creating(function ($query) {
            $query->price ??= 0;
        });
        static::updated(function ($query) {
            if ($query->isDirty('deleted_at')) {
                $query->childs()->delete();
            }
        });
    }

    public function getViewResource(){return ViewServicePrice::class;}
    public function getShowResource(){return ShowServicePrice::class;}
    public function service(){return $this->belongsToModel('Service');}
    public function serviceItem(){return $this->morphTo();}
}
