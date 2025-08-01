<?php

namespace Hanafalah\ModuleService\Models;

use Hanafalah\ModuleService\Enums\Service\Status;
use Hanafalah\ModuleService\Enums\ServiceItem\Flag;
use Hanafalah\ModuleService\Resources\ShowService;
use Hanafalah\ModuleService\Resources\ViewService;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Service extends BaseModel
{
    use HasUlids, HasProps, SoftDeletes;

    public $incrementing  = false;
    protected $primaryKey = 'id';
    protected $keyType    = 'string';
    protected $list       = [
        'id', 'parent_id', 'name', 'status', 
        'reference_id', 'reference_type', 'price',
        'service_label_id',
        'cogs', 'margin', 'props'
    ];
    protected $show       = [];

    protected $casts = [
        'name' => 'string',
        'reference_type' => 'string',
        'service_label_name' => 'string'
    ];

    public function getPropsQuery(): array{
        return [
            'service_label_name' => 'props->prop_service_label->name',
        ];
    }

    protected static function booted(): void{
        parent::booted();
        static::creating(function ($query) {
            $query->service_code ??= static::hasEncoding('SERVICE');
            $query->status ??= self::getStatus(Status::ACTIVE->value);
        });
    }

    public static function getStatus(string $status){
        return Status::from($status)->value;
    }

    public function viewUsingRelation(): array{
        return ['reference'];
    }

    public function viewUsingRelations(): array{
        return ['reference'];
    }

    public function getViewResource(){return ViewService::class;}
    public function getShowResource(){return ShowService::class;}
    public function serviceLabel(){return $this->belongsToModel('ServiceLabel');}
    public function reference(){return $this->morphTo();}
    public function serviceItem(){return $this->hasOneModel('ServiceItem', 'service_id');}
    public function serviceItems(){return $this->hasManyModel('ServiceItem', 'service_id')->whereNull('parent_id')->with('childs');}
    public function additionalItem(){return $this->hasOneModel('ServiceItem', 'service_id')->where('props->flag', Flag::ADDITIONAL_PACKAGE);}
    public function additionalItems(){
        return $this->hasManyModel('ServiceItem', 'service_id')
            ->where('props->flag', Flag::ADDITIONAL_PACKAGE);
    }

    public function paymentSummary(){return $this->morphOneModel('PaymentSummary', 'reference');}
    public function hasService(){
        $service_table = $this->ServiceModel()->getTableName();
        return $this->hasOneThroughModel(
            'Service',
            'ModelHasService',
            $service_table . '.model_id',
            $this->ServiceModel()->getKeyName(),
            $this->getKeyName(),
            $this->ServiceModel()->getForeignKey()
        )->where($service_table . '.model_type', $this->getMorphClass());
    }
    public function modelHasService(){return $this->hasOneModel('ModelHasService', 'service_id');}
    public function servicePrice(){return $this->hasOneModel('ServicePrice', 'service_id');}
    public function servicePrices(){return $this->hasManyModel('ServicePrice', 'service_id');}
}
