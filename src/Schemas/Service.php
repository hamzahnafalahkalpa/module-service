<?php

namespace Hanafalah\ModuleService\Schemas;

use Hanafalah\ModuleService\Contracts\Schemas\Service as ContractsService;
use Hanafalah\LaravelSupport\Supports\PackageManagement;

class Service extends PackageManagement implements ContractsService
{
    protected string $__entity   = 'Service';
    public static $service_model;

    protected array $__cache = [
        'index' => [
            'name'     => 'service',
            'tags'     => ['service', 'service-index'],
            'duration' => 24*60
        ]
    ];
}
