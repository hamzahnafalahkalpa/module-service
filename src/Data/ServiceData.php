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

    #[MapInputName('price')]
    #[MapName('price')]
    public ?int $price = 0;

    #[MapInputName('cogs')]
    #[MapName('cogs')]
    public ?int $cogs = 0;

    #[MapInputName('margin')]
    #[MapName('margin')]
    public ?int $margin = 0;

    #[MapInputName('childs')]
    #[MapName('childs')]
    #[DataCollectionOf(ServiceData::class)]
    public ?array $childs = [];

    #[MapInputName('service_prices')]
    #[MapName('service_prices')]
    #[DataCollectionOf(ServicePriceData::class)]
    public ?array $service_prices = null;

    #[MapInputName('props')]
    #[MapName('props')]
    public ?array $props = [];

    public static function after(self $data): self{
        $new = static::new();
        $data->price ??= 0;
        $data->cogs ??= 0;
        $data->margin ??= 0;
        $props = &$data->props;

        if (isset($data->reference_type) && isset($data->reference_id)){
            $reference = $new->{$data->reference_type.'Model'}();
            $reference = (isset($data->reference_id)) ? $reference->findOrFail($data->reference_id) : $reference;
            $props['prop_reference'] = $reference->toViewApi()->only([
                'id','name','flag','label'
            ]);
        }
        return $data;
    }
}