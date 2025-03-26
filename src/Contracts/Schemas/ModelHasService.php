<?php

namespace Hanafalah\ModuleService\Contracts\Schemas;

use Illuminate\Database\Eloquent\Collection;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;

interface ModelHasService extends DataManagement
{
    public function get(mixed $conditionals = null): Collection;
}
