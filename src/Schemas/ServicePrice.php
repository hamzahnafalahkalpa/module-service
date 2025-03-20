<?php

namespace Hanafalah\ModuleService\Schemas;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Hanafalah\ModuleService\Contracts\ServicePrice as ContractServicePrice;
use Hanafalah\ModuleService\Resources\ServicePrice\ShowServicePrice;
use Hanafalah\LaravelSupport\Supports\PackageManagement;

class ServicePrice extends PackageManagement implements ContractServicePrice
{
    protected array $__guard   = ['id'];
    protected array $__add     = ["service_category_id", "service_item_id", "service_item_type", "price", 'props'];
    protected string $__entity = 'ServicePrice';
    public static $service_price_model;

    protected array $__resources = [
        'view' => ShowServicePrice::class,
        'show' => ShowServicePrice::class
    ];

    public function prepareStoreServicePrice(?array $attributes = null): Model
    {
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

    public function storeServicePrice(): array
    {
        return $this->transaction(function () {
            return $this->prepareStoreServicePrice();
        });
    }

    public function showUsingRelation(): array
    {
        return [];
    }

    public function prepareShowServicePrice(?Model $model = null, array $attributes = null): Model
    {
        $attributes ??= request()->all();

        $model ??= $this->getServicePrice();
        if (!isset($model)) {
            $id = $attributes['id'] ?? null;
            if (!isset($id)) throw new \Exception('Service price not found');

            $model = $this->servicePrice()->with($this->showUsingRelation())->findOrFail($id);
        } else {
            $model = $model->load($this->showUsingRelation());
        }
        return static::$service_price_model = $model;
    }

    public function getServicePrice(): mixed
    {
        return static::$service_price_model;
    }

    public function showServicePrice(?Model $model = null): array
    {
        return $this->transforming($this->__resources['show'], function () use ($model) {
            return $this->prepareShowServicePrice($model);
        });
    }

    public function prepareDeleteServicePrice(?array $attributes = null): bool
    {
        $attributes ??= request()->all();
        if (!isset($attributes['id'])) throw new \Exception('Service price not found');

        $model = $this->servicePrice()->findOrFail($attributes['id']);
        return $model->delete();
    }

    public function deleteServicePrice(): bool
    {
        return $this->transaction(function () {
            return $this->prepareDeleteServicePrice();
        });
    }

    public function servicePrice($conditionals = []): Builder
    {
        $this->booting();
        return $this->ServicePriceModel()->conditionals($conditionals)->withParameters();
    }
}
