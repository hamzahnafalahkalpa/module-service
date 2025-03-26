<?php

namespace Hanafalah\ModuleService\Schemas;

use Hanafalah\ModuleService\Contracts\Schemas\Service as ContractsService;
use Hanafalah\ModuleService\Resources\ShowService;
use Hanafalah\ModuleService\Resources\ViewService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Hanafalah\LaravelSupport\Supports\PackageManagement;

class Service extends PackageManagement implements ContractsService
{
    protected array $__guard     = ['id', "reference_id"];
    protected array $__add       = ['name', "parent_id", "status", "reference_type"];
    protected string $__entity   = 'Service';

    protected array $__resources = [
        'view' => ViewService::class,
        'show' => ShowService::class
    ];

    public static $service_model;

    protected array $__cache = [
        'index' => [
            'name'     => 'service',
            'tags'     => ['service', 'service-index'],
            'forever'  => true
        ]
    ];

    public function addOrChange(?array $attributes = []): self
    {
        $this->updateOrCreate($attributes);
        $this->flushTagsFrom('index');
        return $this;
    }

    public function get(mixed $conditionals = null): Collection
    {
        return $this->service($conditionals)->get();
    }


    public function refind(mixed $id = null): Model|null
    {
        return $this->service()->find($id ??= request()->id);
    }

    public function service(mixed $conditionals = null): Builder
    {
        $this->booting();
        return $this->ServiceModel()->conditionals($this->mergeCondition($conditionals ?? []));
    }

    public function commonService(array $morphs, mixed $conditionals = null): Builder
    {
        return $this->service($conditionals)->whereIn('reference_type', $morphs)
            ->with('reference')->orderBy('props->name', 'asc');
    }

    private function localAddSuffixCache(mixed $suffix): void
    {
        $this->addSuffixCache($this->__cache['index'], "service-index", $suffix);
    }

    public function prepareViewServicePaginate(mixed $cache_reference_type, ?array $morphs = null, int $perPage = 50, array $columns = ['*'], string $pageName = 'page', ?int $page = null, ?int $total = null): LengthAwarePaginator
    {
        $morphs ??= $cache_reference_type;
        $paginate_options = compact('perPage', 'columns', 'pageName', 'page', 'total');
        $cache_reference_type .= '-paginate';
        $this->localAddSuffixCache($cache_reference_type);
        return $this->cacheWhen(!$this->isSearch(), $this->__cache['index'], function () use ($morphs, $paginate_options) {
            return $this->commonService($morphs)->paginate(
                ...$this->arrayValues($paginate_options)
            )->appends(request()->all());
        });
    }

    public function prepareViewServiceList(mixed $cache_reference_type, ?array $morphs = null): Collection
    {
        $morphs ??= $cache_reference_type;
        $this->localAddSuffixCache($cache_reference_type);
        return $this->cacheWhen(!$this->isSearch(), $this->__cache['index'], fn() => $this->commonService($morphs)->get());
    }

    public function viewServicePaginate(mixed $reference_type, ?array $morphs = null, int $perPage = 50, array $columns = ['*'], string $pageName = 'page', ?int $page = null, ?int $total = null): array
    {
        $paginate_options = compact('perPage', 'columns', 'pageName', 'page', 'total');
        return $this->transforming($this->__resources['view'], function () use ($reference_type, $morphs, $paginate_options) {
            return $this->prepareViewServicePaginate($reference_type, $morphs, ...$this->arrayValues($paginate_options));
        });
    }

    public function viewServiceList(mixed $cache_reference_type, ?array $morphs = null): array
    {
        return $this->transforming($this->__resources['view'], function () use ($cache_reference_type, $morphs) {
            return $this->prepareViewServiceList($cache_reference_type, $morphs);
        });
    }
}
