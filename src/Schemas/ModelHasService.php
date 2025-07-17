<?php

namespace Hanafalah\ModuleService\Schemas;

use Hanafalah\ModuleService\Contracts\Schemas\ModelHasService as ContractsServiceItem;
use Hanafalah\LaravelSupport\Supports\PackageManagement;

class ModelHasService extends PackageManagement implements ContractsServiceItem
{
    protected string $__entity = 'ModelHasService';
}
