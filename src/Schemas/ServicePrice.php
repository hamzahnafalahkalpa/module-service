<?php

namespace Hanafalah\ModuleService\Schemas;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Hanafalah\ModuleService\Contracts\Schemas\ServicePrice as ContractServicePrice;
use Hanafalah\ModuleService\Resources\ServicePrice\ShowServicePrice;
use Hanafalah\LaravelSupport\Supports\PackageManagement;

class ServicePrice extends PackageManagement implements ContractServicePrice
{
    protected string $__entity = 'ServicePrice';
    public static $service_price_model;

    public function prepareStoreServicePrice(?array $attributes = null): Model{
        $attributes ??= request()->all();

        if (isset($attributes['id'])) {
            $guard = ['id' => $attributes['id']];
        } else {
            $guard = [
                'service_id' => $attributes['service_id'],
                'service_item_id' => $attributes['service_item_id'],
                'service_item_type' => $attributes['service_item_type']
            ];
        }

        $model = $this->ServicePriceModel()->updateOrCreate($guard, [
            'price' => $attributes['price'] ?? 0
        ]);
        $exceptions = $model->getFillable();
        foreach ($attributes as $key => $value) {
            if (!in_array($key, $exceptions)) {
                $model->{$key} = $value;
            }
        }
        $model->save();
        return static::$service_price_model = $model;
    }
}
