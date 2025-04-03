<?php

namespace Hanafalah\ModuleService\Models;

use Hanafalah\ModuleService\Enums\Service\Status;
use Hanafalah\ModuleService\Enums\ServiceItem\Flag;
use Hanafalah\ModuleService\Resources\ShowService;
use Hanafalah\ModuleService\Resources\ViewService;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;

class Service extends BaseModel
{
    use HasProps, SoftDeletes;

    protected $list = ['id', 'parent_id', 'name', 'status', 'reference_id', 'reference_type', 'props'];
    protected $show = [];

    protected $casts = [
        'name' => 'string'
    ];

    protected static function booted(): void
    {
        parent::booted();
        static::creating(function ($query) {
            if (!isset($query->status)) $query->status = Status::ACTIVE->value;
        });
    }

    public function getViewResource()
    {
        return ViewService::class;
    }

    public function getShowResource()
    {
        return ShowService::class;
    }

    public function reference()
    {
        return $this->morphTo();
    }

    public function serviceItem()
    {
        return $this->hasOneModel('ServiceItem', 'service_id');
    }

    public function serviceItems()
    {
        return $this->hasManyModel('ServiceItem', 'service_id')->whereNull('parent_id')->with('childs');
    }

    public function additionalItem()
    {
        return $this->hasOneModel('ServiceItem', 'service_id')
            ->where('props->flag', Flag::ADDITIONAL_PACKAGE);
    }

    public function additionalItems()
    {
        return $this->hasManyModel('ServiceItem', 'service_id')
            ->where('props->flag', Flag::ADDITIONAL_PACKAGE);
    }

    public function paymentSummary()
    {
        return $this->morphOneModel('PaymentSummary', 'reference');
    }

    public function hasService()
    {
        $service_table = $this->ServiceModel()->getTableName();
        return $this->hasOneThroughModel(
            'Service',
            'ModelHasService',
            $service_table . '.reference_id',
            $this->ServiceModel()->getKeyName(),
            $this->getKeyName(),
            $this->ServiceModel()->getForeignKey()
        )->where($service_table . '.reference_type', $this->getMorphClass());
    }
    public function modelHasService()
    {
        return $this->hasOneModel('ModelHasService', 'service_id');
    }
    public function servicePrice()
    {
        return $this->hasOneModel('ServicePrice', 'service_id');
    }
    public function servicePrices()
    {
        return $this->hasManyModel('ServicePrice', 'service_id');
    }
    public function priceComponent()
    {
        return $this->hasOneModel("PriceComponent", 'service_id');
    }
    public function priceComponents()
    {
        return $this->hasManyModel("PriceComponent", 'service_id');
    }
}
