<?php

namespace Hanafalah\ModuleService\Schemas;

use Hanafalah\ModuleService\Contracts\Schemas\Service as ContractsService;
use Hanafalah\LaravelSupport\Supports\PackageManagement;
use Hanafalah\ModuleService\Contracts\Data\ServiceData;
use Illuminate\Database\Eloquent\Model;

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

    public function prepareStoreService(ServiceData $service_dto): Model{
        $add = [
            'parent_id' => $service_dto->parent_id,
            'name'      => $service_dto->name,
            'price'     => $service_dto->price,
            'margin'    => $service_dto->margin,
            'cogs'      => $service_dto->cogs
        ];
        if (isset($service_dto->id)){
            $guard = ['id' => $service_dto->id];
            $create = [$guard];
        }else{
            $guard = [
                'reference_type' => $service_dto->reference_type,
                'reference_id' => $service_dto->reference_id,
            ];
            $create = [$guard, $add];
        }

        $model = $this->usingEntity()->updateOrCreate(...$create);
        if (isset($service_dto->service_prices) && count($service_dto->service_prices) > 0) {
            foreach ($service_dto->service_prices as $service_price) {
                $service_price->service_id = $service_dto->id;
                $service_price             = $this->schemaContract('service_price')->prepareStoreServicePrice($service_price);
                $model->price             += $service_price->price;
                $model->cogs              += $service_price->cogs;
            }
        }
        $model->margin = ($model->price - $model->cogs)* 100/ $model->cogs ;
        $this->fillingProps($model,$service_dto->props);
        $model->save();
        return static::$service_model = $model;
    }
}
