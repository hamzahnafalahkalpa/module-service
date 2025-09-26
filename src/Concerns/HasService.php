<?php

namespace Hanafalah\ModuleService\Concerns;

use Hanafalah\LaravelSupport\Concerns\Support\HasRequestData;

trait HasService
{
    use HasRequestData;

    protected $__foreign_key = 'service_id';

    protected static function bootHasService()
    {
        static::created(function ($query) {
            if ($query->isUsingService()){
                $service_parent = static::parentService($query->parent_id);
                $parent_id = null;
                if ($service_parent) $parent_id = $service_parent->getKey();
                $service = app(config('app.contracts.Service'))->prepareStoreService(
                    $query->requestDTO(config('app.contracts.ServiceData'),[
                        'parent_id'      => $parent_id,
                        'reference_id'   => $query->id,
                        'reference_type' => $query->getMorphClass(),
                        'reference_model' => $query,
                        'name' => $query->name
                    ])
                );
                if (isset($query->service_code)) {
                    $service->service_code = $query->service_code;
                    // $service->price        = request()->price;
                    $service->save();
                }
            }
        });
        static::deleting(function ($query) {
            if ($query->isUsingService()){
                $query->service()->delete();
            }
        });
        static::updated(function ($query) {
            if ($query->isUsingService()){
                $service_parent = static::parentService($query->parent_id);
                $parent_id = null;
                if ($service_parent) $parent_id = $service_parent->getKey();
                $service = $query->service()->updateOrCreate([
                    'parent_id'         => $parent_id,
                    "reference_id"      => $query->id,
                    "reference_type"    => $query->getMorphClass()
                ], [
                    'name' => $query->name,
                ]);
                if ($query->service_code) {
                    $service->service_code = $query->service_code;
                    $service->save();
                }
            }
        });
    }

    protected function isUsingService(): bool{
        $configs = config('module-service.is_using_services',[]);
        return in_array($this->getMorphClass(), $configs);
    }

    protected static function parentService($parent_id)
    {
        if (isset($parent_id)) {
            $parent = (new static)->find($parent_id)->load('service');
            return $parent->service;
        }
        return null;
    }

    public function initializeHasService()
    {
        $this->mergeFillable([
            $this->__foreign_key
        ]);
    }

    public function service(){
        return $this->morphOneModel('Service', 'reference');
    }

    public function hasServices()
    {
        return $this->belongsToManyModel(
            'Service',
            'ModelHasService',
            $this->ModelHasServiceModel()->getTableName() . '.model_id',
            $this->ServiceModel()->getForeignKey()
        )->where($this->ModelHasServiceModel()->getTableName() . '.model_type', $this->getMorphClass());
    }

    public function hasService()
    {
        $service_table = $this->ServiceModel()->getTableName();
        return $this->hasOneThroughModel(
            'Service',
            'ModelHasService',
            $service_table . '.model_id',
            $this->ServiceModel()->getKeyName(),
            $this->getKeyName(),
            $this->ServiceModel()->getForeignKey()
        )->where($service_table . '.model_type', $this->getMorphClass());
    }

    public function modelHasService()
    {
        return $this->morphOneModel('ModelHasService', 'model');
    }
}
