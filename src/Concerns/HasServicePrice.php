<?php

namespace Hanafalah\ModuleService\Concerns;

trait HasServicePrice
{

    protected static function bootHasServicePrice()
    {
        static::deleted(function ($query) {
            $query->servicePrice()->delete();
        });
    }

    public function servicePrice()
    {
        return $this->morphOneModel('ServicePrice', 'service_item');
    }
    public function servicePrices()
    {
        return $this->morphManyModel('ServicePrice', 'service_item');
    }
}
