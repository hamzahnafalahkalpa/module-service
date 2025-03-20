<?php

namespace Hanafalah\ModuleService\Schemas;

use Hanafalah\ModuleService\Contracts\ModelHasService as ContractsServiceItem;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Hanafalah\LaravelSupport\Supports\PackageManagement;

class ModelHasService extends PackageManagement implements ContractsServiceItem
{
    protected array $__guard   = ['id', "reference_id"];
    protected array $__add     = ["service_id", "reference_type"];
    protected string $__entity = 'ModelHasService';

    public function booting(): self
    {
        static::$__class = $this;
        static::$__model = $this->{$this->__entity . "Model"}();
        return $this;
    }


    public function addOrChange(?array $attributes = []): self
    {
        $this->updateOrCreate($attributes);
        return $this;
    }

    public function get(mixed $conditionals = null): Collection
    {
        return $this->modelHasService()->get();
    }


    public function refind(mixed $id = null): Model|null
    {
        return $this->modelHasService()->find($id ??= request());
    }

    protected function modelHasService($conditionals = []): Builder
    {
        $this->booting();
        return $this->getModel()->conditionals($conditionals);
    }
}
