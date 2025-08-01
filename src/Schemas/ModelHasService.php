<?php

namespace Hanafalah\ModuleService\Schemas;

use Hanafalah\ModuleService\Contracts\Schemas\ModelHasService as ContractsModelHasService;
use Hanafalah\LaravelSupport\Supports\PackageManagement;
use Hanafalah\ModuleService\Contracts\Data\ModelHasServiceData;
use Illuminate\Database\Eloquent\Model;

class ModelHasService extends PackageManagement implements ContractsModelHasService
{
    protected string $__entity = 'ModelHasService';
    public $model_has_service_model;

    public function prepareStoreModelHasService(ModelHasServiceData $model_has_service_dto): Model{
        $add = [
            'model_type' => $model_has_service_dto->model_type,
            'model_id'   => $model_has_service_dto->model_id,
            'service_id' => $model_has_service_dto->service_id
        ];
        if (isset($model_has_service_dto->id)){
            $guard = ['id' => $model_has_service_dto->id];
            $create = [$guard,$add];
        }else{
            $create = [$add];
        }

        $model_has_service = $this->usingEntity()->updateOrCreate(...$create);
        $this->fillingProps($model_has_service,$model_has_service_dto->props);
        $model_has_service->save();
        return $this->model_has_service_model = $model_has_service;
    }
}
