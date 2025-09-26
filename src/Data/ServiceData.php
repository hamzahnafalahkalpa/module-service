<?php

namespace Hanafalah\ModuleService\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleService\Contracts\Data\ServiceData as DataServiceData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class ServiceData extends Data implements DataServiceData{
    #[MapInputName('id')]
    #[MapName('id')]
    public mixed $id = null;
    
    #[MapInputName('parent_id')]
    #[MapName('parent_id')]
    public mixed $parent_id = null;

    #[MapInputName('name')]
    #[MapName('name')]
    public ?string $name = null;

    #[MapInputName('status')]
    #[MapName('status')]
    public ?string $status = null;
    
    #[MapInputName('reference_type')]
    #[MapName('reference_type')]
    public ?string $reference_type = null;

    #[MapInputName('reference_id')]
    #[MapName('reference_id')]
    public mixed $reference_id = null;

    #[MapInputName('reference_model')]
    #[MapName('reference_model')]
    public ?object $reference_model = null;

    #[MapInputName('service_label_id')]
    #[MapName('service_label_id')]
    public mixed $service_label_id = null;

    #[MapInputName('price')]
    #[MapName('price')]
    public ?int $price = 0;

    #[MapInputName('cogs')]
    #[MapName('cogs')]
    public ?int $cogs = 0;

    #[MapInputName('margin')]
    #[MapName('margin')]
    public ?float $margin = 0;

    #[MapInputName('childs')]
    #[MapName('childs')]
    #[DataCollectionOf(ServiceData::class)]
    public ?array $childs = [];

    #[MapInputName('service_prices')]
    #[MapName('service_prices')]
    #[DataCollectionOf(ServicePriceData::class)]
    public ?array $service_prices = null;

    #[MapInputName('service_items')]
    #[MapName('service_items')]
    #[DataCollectionOf(ServiceItemData::class)]
    public ?array $service_items = null;

    #[MapInputName('props')]
    #[MapName('props')]
    public ?array $props = [];

    public static function after(self $data): self{
        $new = static::new();
        $props = &$data->props;
        
        $data->price ??= 0;
        $data->cogs ??= 0;
        $data->margin ??= 0;

        $service_label = $new->ServiceLabelModel();
        if (isset($data->service_label_id)) $service_label = $service_label->findOrFail($data->service_label_id);
        $props['prop_service_label'] = $service_label->toViewApiOnlies('id','name','flag','label');
        return $data;
    }
}