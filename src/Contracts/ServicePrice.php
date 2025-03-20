<?php

namespace Hanafalah\ModuleService\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Hanafalah\LaravelSupport\Contracts\DataManagement;

interface ServicePrice extends DataManagement
{
    public function prepareStoreServicePrice(?array $attributes = null): Model;
    public function storeServicePrice(): array;
    public function showUsingRelation(): array;
    public function prepareShowServicePrice(?Model $model = null, array $attributes = null): Model;
    public function getServicePrice(): mixed;
    public function showServicePrice(?Model $model = null): array;
    public function prepareDeleteServicePrice(?array $attributes = null): bool;
    public function deleteServicePrice(): bool;
    public function servicePrice($conditionals = []): Builder;
}
