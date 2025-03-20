<?php

namespace Gii\ModuleService\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Gii\Service-module\Services\
 */
class ModuleServiceItem extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'service-item';
    }
}
