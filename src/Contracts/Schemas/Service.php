<?php

namespace Hanafalah\ModuleService\Contracts\Schemas;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;

interface Service extends DataManagement
{
    public function addOrChange(?array $attributes = []): self;
    public function get(mixed $conditionals = null): Collection;
    public function refind(mixed $id = null): Model|null;
    public function service(mixed $conditionals = null): Builder;
    public function commonService(array $morphs, mixed $conditionals = null): Builder;
    public function prepareViewServicePaginate(mixed $cache_reference_type, ?array $morphs = null, int $perPage = 50, array $columns = ['*'], string $pageName = 'page', ?int $page = null, ?int $total = null): LengthAwarePaginator;
    public function prepareViewServiceList(mixed $cache_reference_type, ?array $morphs = null): Collection;
    public function viewServicePaginate(mixed $reference_type, ?array $morphs = null, int $perPage = 50, array $columns = ['*'], string $pageName = 'page', ?int $page = null, ?int $total = null): array;
    public function viewServiceList(mixed $reference_type, ?array $morphs = null): array;
}
