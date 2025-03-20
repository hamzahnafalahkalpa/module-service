<?php

use Hanafalah\ModuleService\{
    Models as ModuleService,
    Contracts
};

return [
    'contracts'  => [
        'model_has_service' => Contracts\ModelHasService::class,
        'module_service'    => Contracts\ModuleService::class,
        'service'           => Contracts\Service::class,
        'service_item'      => Contracts\ServiceItem::class,
        'service_price'     => Contracts\ServicePrice::class
    ],
    'database'   => [
        'models' => [
            'Service'         => ModuleService\Service::class,
            'ServiceItem'     => ModuleService\ServiceItem::class,
            'ServicePrice'    => ModuleService\ServicePrice::class,
            'ServiceCategory' => ModuleService\ServiceCategory::class,
            'ModelHasService' => ModuleService\ModelHasService::class
        ]
    ],
];
