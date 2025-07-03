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
    public ?int $price = 0;

    #[MapInputName('tax')]
    #[MapName('tax')]
    public ?int $tax = 0;

    #[MapInputName('margin')]
    #[MapName('margin')]
    public ?int $margin = 0;

    #[MapInputName('props')]
    #[MapName('props')]
    public ?array $props = [];

    public static function after(self $data): self{
        $new = static::new();
        $props = &$data->props;
        
        if (!isset($data->service_id)){
            $reference = $new->{$data->service_item_type.'Model'}()->findOrFail($data->service_item_id);
            $data->service_id = $reference->service->id;
        }

        $data->price ??= $new->calculatePrice();
        return $data;
    }

    private function calculatePrice(){
        $price = $this->cogs + $this->cogs * $this->margin/100;
        $this->props['total_tax'] = $price * $this->tax/100;
        return $price;
    }
}