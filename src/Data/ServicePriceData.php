<?php

namespace Hanafalah\ModuleService\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleService\Contracts\Data\ServicePriceData as DataServicePriceData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\RequiredWithout;
use Spatie\LaravelData\Attributes\Validation\RequiredWithoutAll;

class ServicePriceData extends Data implements DataServicePriceData{
    #[MapInputName('id')]
    #[MapName('id')]
    public mixed $id = null;

    #[MapInputName('parent_id')]
    #[MapName('parent_id')]
    public mixed $parent_id = null;
    
    #[MapInputName('service_id')]
    #[MapName('service_id')]
    #[RequiredWithoutAll(['service_item_type', 'service_item_id'])]
    public mixed $service_id = null;

    #[MapInputName('service_item_type')]
    #[MapName('service_item_type')]
    #[RequiredWithout('service_id')]
    public string $service_item_type;

    #[MapInputName('service_item_id')]
    #[MapName('service_item_id')]
    #[RequiredWithout('service_id')]
    public mixed $service_item_id;

    #[MapInputName('cogs')]
    #[MapName('cogs')]
    public ?int $cogs = 0;

    #[MapInputName('price')]
    #[MapName('price')]
    public ?int $price = null;

    #[MapInputName('tax')]
    #[MapName('tax')]
    public ?int $tax = 0;

    #[MapInputName('margin')]
    #[MapName('margin')]
    public ?float $margin = 0;

    #[MapInputName('props')]
    #[MapName('props')]
    public ?array $props = [];

    public static function after(self $data): self{
        $new = static::new();
        $props = &$data->props;
        $data->price ??= $new->calculatePrice($data);
        return $data;
    }

    private function calculatePrice($data){
        $price = &$data->price;
        $price = $data->cogs + $data->cogs * $data->margin/100;
        $data->props['total_tax'] = $price * $data->tax/100;
        return $price;
    }
}