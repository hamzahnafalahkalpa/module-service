<?php

namespace Hanafalah\ModuleService\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Hanafalah\Service-module\Services\
 */
class ModuleServiceItem extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'service-item';
    }
}
