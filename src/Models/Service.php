<?php

namespace Gii\ModuleService\Models;

use Gii\ModuleService\Enums\Service\Status;
use Gii\ModuleService\Enums\ServiceItem\Flag;
use Gii\ModuleService\Resources\ShowService;
use Gii\ModuleService\Resources\ViewService;
use Illuminate\Database\Eloquent\SoftDeletes;
use Zahzah\LaravelHasProps\Concerns\HasProps;
use Zahzah\LaravelSupport\Models\BaseModel;

class Service extends BaseModel {
    use HasProps, SoftDeletes;

    protected $list = ['id','parent_id','name','status','reference_id','reference_type','props'];
    protected $show = [];

    protected $casts = [
        'name' => 'string'
    ];

    protected static function booted(): void{
        parent::booted();
        static::creating(function($query){
            if (!isset($query->status)) $query->status = Status::ACTIVE->value;
        });
    }

    public function toViewApi(){
        return new ViewService($this);
    }

    public function toShowApi(){
        return new ShowService($this);
    }

    public function reference(){return $this->morphTo();}
    
    public function serviceItem(){
        return $this->hasOneModel('ServiceItem','service_id');
    }

    public function serviceItems(){
        return $this->hasManyModel('ServiceItem','service_id')->whereNull('parent_id')->with('childs');
    }
    
    public function additionalItem(){
        return $this->hasOneModel('ServiceItem','service_id')
                    ->where('props->flag',Flag::ADDITIONAL_PACKAGE);
    }
    
    public function additionalItems(){
        return $this->hasManyModel('ServiceItem','service_id')
                    ->where('props->flag',Flag::ADDITIONAL_PACKAGE);
    }
    
    public function paymentSummary(){
        return $this->morphOneModel('PaymentSummary','reference');
    }

    public function hasService(){
        $service_table = $this->ServiceModel()->getTableName();
        return $this->hasOneThroughModel(
            'Service',
            'ModelHasService',
            $service_table.'.reference_id',
            $this->ServiceModel()->getKeyName(),
            $this->getKeyName(),
            $this->ServiceModel()->getForeignKey()
        )->where($service_table.'.reference_type',$this->getMorphClass());
    }
    public function modelHasService(){return $this->hasOneModel('ModelHasService','service_id');}
    public function servicePrice(){return $this->hasOneModel('ServicePrice','service_id');}
    public function servicePrices(){return $this->hasManyModel('ServicePrice','service_id');}
    public function priceComponent(){return $this->hasOneModel("PriceComponent",'service_id');}
    public function priceComponents(){return $this->hasManyModel("PriceComponent",'service_id');}
}