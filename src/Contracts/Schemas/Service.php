<?php

namespace Hanafalah\ModuleService\Contracts\Schemas;

use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;

/**
 * @see \Hanafalah\ModuleService\Schemas\Service
 * @method self setParamLogic(string $logic, bool $search_value = false, ?array $optionals = [])
 * @method self conditionals(mixed $conditionals)
 * @method mixed getService()
 * @method ?Model prepareShowService(?Model $model = null, ?array $attributes = null)
 * @method array showService(?Model $model = null)
 * @method array viewServiceList()
 * @method Collection prepareViewServiceList(? array $attributes = null)
 * @method LengthAwarePaginator prepareViewServicePaginate(PaginateData $paginate_dto)
 * @method array viewServicePaginate(?PaginateData $paginate_dto = null)
 * @method Builder function service(mixed $conditionals = null)
 */
interface Service extends DataManagement{}
