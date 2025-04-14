<?php

namespace Hanafalah\ModuleService\Contracts\Schemas;

use Hanafalah\LaravelSupport\Contracts\Data\PaginateData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;

interface Service extends DataManagement
{
    public function commonService(array $morphs, mixed $conditionals = null): Builder;
    public function prepareViewServicePaginate(mixed $cache_reference_type, ?array $morphs = null, PaginateData $paginate_dto): LengthAwarePaginator;
    public function viewServicePaginate(mixed $cache_reference_type, ?array $morphs = null, ? PaginateData $paginate_dto = null): array;
    public function prepareViewServiceList(mixed $cache_reference_type, ?array $morphs = null): Collection;
    public function viewServiceList(mixed $cache_reference_type, ?array $morphs = null): array;
    public function service(mixed $conditionals = null): Builder;
    
}
