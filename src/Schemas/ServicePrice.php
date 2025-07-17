<?php

namespace Hanafalah\ModuleService\Schemas;

use Illuminate\Database\Eloquent\Model;
use Hanafalah\ModuleService\Contracts\Schemas\ServicePrice as ContractServicePrice;
use Hanafalah\LaravelSupport\Supports\PackageManagement;
use Hanafalah\ModuleService\Contracts\Data\ServicePriceData;

class ServicePrice extends PackageManagement implements ContractServicePrice
{
    protected string $__entity = 'ServicePrice';
    public static $service_price_model;

    public function prepareStoreServicePrice(ServicePriceData $service_price_dto): Model{
        $add = [
            'price' => $service_price_dto->price,
            'cogs' => $service_price_dto->cogs,
            'margin' => floatval($service_price_dto->margin)
        ];
        if (isset($service_price_dto->id)) {
            $guard = ['id' => $service_price_dto->id];
        } else {
            $guard = [
                'service_id'        => $service_price_dto->service_id,
                'service_item_id'   => $service_price_dto->service_item_id,
                'service_item_type' => $service_price_dto->service_item_type
            ];
        }

        $model = $this->ServicePriceModel()->updateOrCreate($guard, $add);
        $this->fillingProps($model, $service_price_dto->props);
        $model->save();
        return static::$service_price_model = $model;
    }
}
