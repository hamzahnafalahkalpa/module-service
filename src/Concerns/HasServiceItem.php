<?php

namespace Hanafalah\ModuleService\Concerns;

use Hanafalah\ModuleService\Enums;

trait HasServiceItem
{
    protected static function bootHasServiceItem()
    {
        static::deleting(function ($query) {
            $service_item = $query->serviceItem;
            if (isset($service_item)) throw new \Exception("Service item is already in use");
        });
    }

    public function serviceItem()
    {
        return $this->morphOneModel('ServiceItem', 'reference');
    }
    public function serviceItems()
    {
        return $this->morphManyModel('ServiceItem', 'reference');
    }
}
