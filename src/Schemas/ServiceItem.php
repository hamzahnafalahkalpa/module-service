<?php

namespace Hanafalah\ModuleService\Schemas;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Hanafalah\ModuleService\Contracts\Schemas\ServiceItem as ContractServiceItem;
use Illuminate\Database\Eloquent\Relations\Relation;
use Hanafalah\LaravelSupport\Supports\PackageManagement;

class ServiceItem extends PackageManagement implements ContractServiceItem
{
    protected string $__entity = 'ServiceItem';
    public $service_item_model;

    public function prepareStoreServiceItem(?array $attributes = null): Model{
        $attributes ??= request()->all();

        if (!isset($attributes['service_id'])) throw new \Exception('service_id is required');

        if (!isset($attributes['reference_id']) || !isset($attributes['reference_type'])) throw new \Exception('reference_id and reference_type is required');

        $item = app(Relation::morphMap()[$attributes['reference_type']])->findOrFail($attributes['reference_id']);

        $model = $this->ServiceItemModel()->firstOrCreate([
            'parent_id'       => $attributes['parent_id'] ?? null,
            'service_id'      => $attributes['service_id'],
            'reference_id'    => $item->getKey(),
            'reference_type'  => $item->getMorphClass()
        ], [
            'name'  => $item->name ?? null,
            'price' => $item->price ?? 0
        ]);

        $model->name = $item->name;

        $exceptions = $model->getFillable();
        $exceptions[] = 'service_price';
        foreach ($attributes as $key => $value) {
            if (!in_array($key, $exceptions)) $model->{$key} = $value;
        }
        $model->save();

        $attributes['service_price'] ??= [];
        $attributes['service_price']['service_id']        = $attributes['service_id'];
        $attributes['service_price']['service_item_id']   = $model->getKey();
        $attributes['service_price']['service_item_type'] = $model->getMorphClass();
        $attributes['service_price']['price']           ??= $item->price ?? 0;

        $service_price_schema = $this->schemaContract('service_price');
        $service_price = $service_price_schema->prepareStoreServicePrice($attributes['service_price']);
        return $this->service_item_model = $model;
    }

    public function prepareViewServiceItemList(?array $attributes = null): array
    {
        $attributes ??= request()->all();

        $model = $this->serviceItem()->conditionals($this->mergeCondition([]))->get();

        return $this->service_item_model = $model;
    }

    public function addOrChange(?array $attributes = []): self
    {
        $this->updateOrCreate($attributes);
        return $this;
    }

    public function get(mixed $conditionals = null): Collection
    {
        return $this->serviceItem()->get();
    }


    public function refind(mixed $id = null): Model|null
    {
        return $this->serviceItem()->find($id ??= request());
    }

    protected function serviceItem($conditionals = []): Builder
    {
        $this->booting();
        return $this->ServiceItemModel()->conditionals($conditionals)->withParameters()->orderBy('props->name', 'asc');
    }
}
