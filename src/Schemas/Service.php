<?php

namespace Hanafalah\ModuleService\Schemas;

use Hanafalah\ModuleService\Contracts\Schemas\Service as ContractsService;
use Hanafalah\LaravelSupport\Supports\PackageManagement;
use Hanafalah\ModuleService\Contracts\Data\ServiceData;
use Illuminate\Database\Eloquent\Model;

class Service extends PackageManagement implements ContractsService
{
    protected string $__entity   = 'Service';
    public $service_model;

    protected array $__cache = [
        'index' => [
            'name'     => 'service',
            'tags'     => ['service', 'service-index'],
            'duration' => 24*60
        ]
    ];

    public function prepareStoreService(ServiceData $service_dto): Model{
        $add = [
            'service_label_id' => $service_dto->service_label_id,
            'parent_id' => $service_dto->parent_id,
            'name'      => $service_dto->name,
            'price'     => $service_dto->price ?? 0,
            'margin'    => $service_dto->margin ?? 0,
            'cogs'      => $service_dto->cogs ?? 0
        ];
        if (isset($service_dto->id)){
            $guard = ['id' => $service_dto->id];
        }else{
            $guard = [
                'reference_type' => $service_dto->reference_type,
                'reference_id' => $service_dto->reference_id,
            ];
        }
        $create = [$guard,$add];

        $model = $this->usingEntity()->updateOrCreate(...$create);
        $model->load(['reference' => fn($q) => $q->withoutGlobalScopes() ]);
        $reference_model = $service_dto->reference_model ?? $model->reference;
        $service_dto->props['prop_reference'] = $reference_model->toViewApi()->resolve();
        if (isset($service_dto->service_prices) && count($service_dto->service_prices) > 0) {
            foreach ($service_dto->service_prices as $service_price) {
                $service_price->service_id = $model->getKey();
                $service_price             = $this->schemaContract('service_price')->prepareStoreServicePrice($service_price);
                $model->price             += $service_price->price;
                $model->cogs              += $service_price->cogs;
            }
        }

        if (isset($service_dto->service_items) && count($service_dto->service_items) > 0) {
            foreach ($service_dto->service_items as &$service_item) {
                $service_item->service_id = $model->getKey();
                $this->schemaContract('service_item')->prepareStoreServiceItem($service_item);
            }
        }

        $model->margin = ($model->cogs > 0)
            ? floatval(($model->price - $model->cogs)* 100/ $model->cogs)
            : 0;
        $this->fillingProps($model,$service_dto->props);
        $model->save();
        return $this->service_model = $model;
    }
}
