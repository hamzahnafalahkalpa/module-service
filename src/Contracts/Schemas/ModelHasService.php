<?php

namespace Hanafalah\ModuleService\Contracts\Schemas;

use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;

/**
 * @see \Hanafalah\ModuleService\Schemas\ModelHasService
 * @method self setParamLogic(string $logic, bool $search_value = false, ?array $optionals = [])
 * @method self conditionals(mixed $conditionals)
 * @method array storeModelHasService(?ModelHasServiceData $rab_work_list_dto = null)
 * @method bool deleteModelHasService()
 * @method bool prepareDeleteModelHasService(? array $attributes = null)
 * @method mixed getModelHasService()
 * @method ?Model prepareShowModelHasService(?Model $model = null, ?array $attributes = null)
 * @method array showModelHasService(?Model $model = null)
 * @method array viewModelHasServiceList()
 * @method Collection prepareViewModelHasServiceList(? array $attributes = null)
 * @method LengthAwarePaginator prepareViewModelHasServicePaginate(PaginateData $paginate_dto)
 * @method array viewModelHasServicePaginate(?PaginateData $paginate_dto = null)
 * @method Builder function modelHasService(mixed $conditionals = null)
 */
interface ModelHasService extends DataManagement{}
