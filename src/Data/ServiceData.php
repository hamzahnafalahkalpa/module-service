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

    #[MapInputName('childs')]
    #[MapName('childs')]
    #[DataCollectionOf(ServiceData::class)]
    public ?array $childs = [];

    #[MapInputName('props')]
    #[MapName('props')]
    public ?array $props = [];
}