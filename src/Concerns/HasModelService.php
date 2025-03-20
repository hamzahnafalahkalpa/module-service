<?php

namespace Gii\ModuleService\Concerns;

trait HasModelService {
    public function modelHasService(){
        return $this->morphOneModel('ModelHasService','reference');
    }

    public function service(){
        return $this->hasOneThroughModel(
            'Service',
            'ModelHasService',
            'reference_id',
            $this->ServiceModel()->getKeyName(),
            $this->getKeyName(),
            $this->ServiceModel()->getForeignKey()
        )->where('reference_type',$this->getMorphClass());
    }

    public function services(){
        return $this->belongsToManyModel(
            'Service',
            'ModelHasService',
            'reference_id',
            $this->ServiceModel()->getForeignKey()
        )->where('reference_type',$this->getMorphClass());
    }
}