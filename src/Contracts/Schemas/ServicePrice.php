<?php

namespace Hanafalah\ModuleService\Contracts\Schemas;

use Hanafalah\LaravelSupport\Contracts\Supports\DataManagement;

/**
 * @see \Hanafalah\ModuleService\Schemas\ServicePrice
 * @method self conditionals(mixed $conditionals)
 * @method array storeServicePrice(?ServicePriceData $rab_work_list_dto = null)
 * @method bool deleteServicePrice()
 * @method bool prepareDeleteServicePrice(? array $attributes = null)
 * @method mixed getServicePrice()
 * @method ?Model prepareShowServicePrice(?Model $model = null, ?array $attributes = null)
 * @method array showServicePrice(?Model $model = null)
 * @method array viewServicePriceList()
 * @method Collection prepareViewServicePriceList(? array $attributes = null)
 * @method LengthAwarePaginator prepareViewServicePricePaginate(PaginateData $paginate_dto)
 * @method array viewServicePricePaginate(?PaginateData $paginate_dto = null)
 * @method Builder function servicePrice(mixed $conditionals = null)
 */
interface ServicePrice extends DataManagement{}
