<?php

namespace Hanafalah\ModuleService\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleService\Contracts\Data\ServiceItemData as DataServiceItemData;
use Hanafalah\ModuleService\Contracts\Data\ServicePriceData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class ServiceItemData extends Data implements DataServiceItemData{
    #[MapInputName('id')]
    #[MapName('id')]
    public mixed $id = null;

    #[MapInputName('parent_id')]
    #[MapName('parent_id')]
    public mixed $parent_id = null;

    #[MapInputName('service_id')]
    #[MapName('service_id')]
    public mixed $service_id = null;

    #[MapInputName('reference_id')]
    #[MapName('reference_id')]
    public mixed $reference_id = null;

    #[MapInputName('reference_type')]
    #[MapName('reference_type')]
    public ?string $reference_type = null;

    #[MapInputName('name')]
    #[MapName('name')]
    public ?string $name = null;

    #[MapInputName('price')]
    #[MapName('price')]
    public ?int $price = 0;

    #[MapInputName('service_price')]
    #[MapName('service_price')]
    public ?ServicePriceData $service_price = null;

    #[MapInputName('props')]
    #[MapName('props')]
    public ?array $props = null;

}