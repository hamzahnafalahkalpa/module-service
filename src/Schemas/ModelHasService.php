<?php

namespace Hanafalah\ModuleService\Schemas;

use Hanafalah\ModuleService\Contracts\Schemas\ModelHasService as ContractsServiceItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Hanafalah\LaravelSupport\Supports\PackageManagement;

class ModelHasService extends PackageManagement implements ContractsServiceItem
{
    protected string $__entity = 'ModelHasService';
}
