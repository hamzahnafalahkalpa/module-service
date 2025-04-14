<?php

namespace Hanafalah\ModuleService\Schemas;

use Hanafalah\LaravelSupport\Contracts\Data\PaginateData;
use Hanafalah\ModuleService\Contracts\Schemas\Service as ContractsService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Hanafalah\LaravelSupport\Supports\PackageManagement;

class Service extends PackageManagement implements ContractsService
{
    protected array $__guard     = ['id', "reference_id"];
    protected array $__add       = ['name', "parent_id", "status", "reference_type"];
    protected string $__entity   = 'Service';

    public static $service_model;

    protected array $__cache = [
        'index' => [
            'name'     => 'service',
            'tags'     => ['service', 'service-index'],
            'forever'  => true
        ]
    ];

    public function commonService(array $morphs, mixed $conditionals = null): Builder{
        return $this->service($conditionals)->whereIn('reference_type', $morphs)
            ->with('reference')->orderBy('props->name', 'asc');
    }

    private function localAddSuffixCache(mixed $suffix): void{
        $this->addSuffixCache($this->__cache['index'], "service-index", $suffix);
    }

    public function prepareViewServicePaginate(mixed $cache_reference_type, ?array $morphs = null, PaginateData $paginate_dto): LengthAwarePaginator{
        $morphs              ??= $cache_reference_type;
        $cache_reference_type .= '-paginate';
        $this->localAddSuffixCache($cache_reference_type);
        return $this->cacheWhen(!$this->isSearch(), $this->__cache['index'], function () use ($morphs, $paginate_dto) {
            return $this->commonService($morphs)->paginate(...$paginate_dto->toArray())->appends(request()->all());
        });
    }

    public function viewServicePaginate(mixed $cache_reference_type, ?array $morphs = null, ? PaginateData $paginate_dto = null): array{
        return $this->viewEntityResource(function() use ($cache_reference_type,$morphs,$paginate_dto){
            return $this->prepareViewServicePaginate($cache_reference_type, $morphs, $paginate_dto ?? $this->requestDTO(PaginateData::class));
        });
    }


    public function prepareViewServiceList(mixed $cache_reference_type, ?array $morphs = null): Collection{
        $morphs ??= $cache_reference_type;
        $this->localAddSuffixCache($cache_reference_type);
        return $this->cacheWhen(!$this->isSearch(), $this->__cache['index'], fn() => $this->commonService($morphs)->get());
    }

    public function viewServiceList(mixed $cache_reference_type, ?array $morphs = null): array{
        return $this->viewEntityResource(function() use ($cache_reference_type,$morphs){
            return $this->prepareViewServiceList($cache_reference_type, $morphs);
        });
    }

    public function service(mixed $conditionals = null): Builder{
        $this->booting();
        return $this->ServiceModel()->conditionals($this->mergeCondition($conditionals ?? []))->withParameters()->orderBy('name','asc');
    }
}
