<?php

namespace Hanafalah\ModuleService\Models;

use Hanafalah\ModuleService\Enums;
use Hanafalah\ModuleService\Enums\ServiceItem\Flag;
use Hanafalah\LaravelHasProps\Concerns\HasProps;

class ServiceCategory extends ServiceItem
{
    use HasProps;
    protected $table = 'service_items';

    protected static function booted(): void
    {
        parent::booted();
        static::addGlobalScope('service_category', function ($query) {
            $query->setItemFlag([Enums\ServiceItem\Flag::CATEGORY_PACKAGE->value]);
        });
    }

    //EIGER SECTION
    public function servicePrice()
    {
        return $this->hasOneModel('ServicePrice');
    }
    public function servicePrices()
    {
        return $this->hasManyModel('ServicePrice');
    }
}
