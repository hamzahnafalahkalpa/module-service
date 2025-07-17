<?php

namespace Hanafalah\ModuleService\Schemas;

use Hanafalah\LaravelSupport\Schemas\Unicode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Hanafalah\ModuleService\Contracts\Data\ServiceLabelData;
use Hanafalah\ModuleService\Contracts\Schemas\ServiceLabel as ContractsServiceLabel;

class ServiceLabel extends Unicode implements ContractsServiceLabel
{
    protected string $__entity = 'ServiceLabel';
    public static $service_label_model;

    protected array $__cache = [
        'index' => [
            'name'     => 'service_label',
            'tags'     => ['service_label', 'service_label-index'],
            'forever'  => true
        ]
    ];

    public function prepareStoreServiceLabel(ServiceLabelData $service_label_dto): Model{     
        $service_label = $this->prepareStoreUnicode($service_label_dto);       
        return static::$service_label_model = $service_label;
    }

    public function serviceLabel(mixed $conditionals = null): Builder{
        return $this->unicode($conditionals);
    }
}
