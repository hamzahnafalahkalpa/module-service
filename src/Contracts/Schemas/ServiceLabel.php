<?php

namespace Hanafalah\ModuleService\Contracts\Schemas;

use Hanafalah\LaravelSupport\Contracts\Schemas\Unicode;
use Hanafalah\ModuleService\Contracts\Data\ServiceLabelData;
//use Hanafalah\ModuleService\Contracts\Data\ServiceLabelUpdateData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @see \Hanafalah\ModuleService\Schemas\ServiceLabel
 * @method mixed export(string $type)
 * @method self conditionals(mixed $conditionals)
 * @method array updateServiceLabel(?ServiceLabelData $service_label_dto = null)
 * @method Model prepareUpdateServiceLabel(ServiceLabelData $service_label_dto)
 * @method bool deleteServiceLabel()
 * @method bool prepareDeleteServiceLabel(? array $attributes = null)
 * @method mixed getServiceLabel()
 * @method ?Model prepareShowServiceLabel(?Model $model = null, ?array $attributes = null)
 * @method array showServiceLabel(?Model $model = null)
 * @method Collection prepareViewServiceLabelList()
 * @method array viewServiceLabelList()
 * @method LengthAwarePaginator prepareViewServiceLabelPaginate(PaginateData $paginate_dto)
 * @method array viewServiceLabelPaginate(?PaginateData $paginate_dto = null)
 * @method array storeServiceLabel(?ServiceLabelData $service_label_dto = null)
 * @method Collection prepareStoreMultipleServiceLabel(array $datas)
 * @method array storeMultipleServiceLabel(array $datas)
 */

interface ServiceLabel extends Unicode
{
    public function prepareStoreServiceLabel(ServiceLabelData $service_label_dto): Model;
    public function serviceLabel(mixed $conditionals = null): Builder;
}