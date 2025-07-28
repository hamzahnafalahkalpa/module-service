<?php

namespace Hanafalah\ModuleService\Schemas;

use Illuminate\Database\Eloquent\Model;
use Hanafalah\ModuleService\Contracts\Schemas\ServiceItem as ContractServiceItem;
use Hanafalah\LaravelSupport\Supports\PackageManagement;
use Hanafalah\ModuleService\Contracts\Data\ServiceItemData;

class ServiceItem extends PackageManagement implements ContractServiceItem
{
    protected string $__entity = 'ServiceItem';
    public $service_item_model;

    public function prepareStoreServiceItem(ServiceItemData $service_item_dto): Model{
        $add = [
            'name'  => $service_item_dto->name,
            'price' => $service_item_dto->price ?? 0
        ];
        if (isset($service_item_dto->id)){
            $guard = ['id' => $service_item_dto->id];
        }else{
            $guard = [
                'parent_id'       => $service_item_dto->parent_id,
                'service_id'      => $service_item_dto->service_id,
                'reference_id'    => $service_item_dto->reference_id,
                'reference_type'  => $service_item_dto->reference_type
            ];
        }
        $model = $this->usingEntity()->firstOrCreate($guard,$add);
        $this->fillingProps($model,$service_item_dto->props);
        $model->save();

        if (isset($service_item_dto->service_price)){
            $service_price_dto = &$service_item_dto->service_price;
            $service_price_dto->service_item_id = $model->getKey();
            $service_price_dto->service_item_type = $model->getMorphClass();
            $service_price_dto->service_id = $service_item_dto->service_id;
            $this->schemaContract('service_price')->prepareStoreServicePrice($service_price_dto);
        }
        return $this->service_item_model = $model;
    }
}
