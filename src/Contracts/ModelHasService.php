<?php

namespace Hanafalah\ModuleService\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Hanafalah\LaravelSupport\Contracts\DataManagement;

interface ModelHasService extends DataManagement
{
    public function get(mixed $conditionals = null): Collection;
}
