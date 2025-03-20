<?php

namespace Gii\ModuleService\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Zahzah\LaravelSupport\Contracts\DataManagement;

interface ModelHasService extends DataManagement{
    public function get(mixed $conditionals=null) : Collection;
}