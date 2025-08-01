<?php

namespace Hanafalah\ModuleService\Data;

use Hanafalah\LaravelSupport\Supports\Data;
use Hanafalah\ModuleService\Contracts\Data\ModelHasServiceData as DataModelHasServiceData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;

class ModelHasServiceData extends Data implements DataModelHasServiceData{
    #[MapInputName('id')]
    #[MapName('id')]
    public mixed $id = null;
    
    #[MapInputName('model_type')]
    #[MapName('model_type')]
    public ?string $model_type = null;

    #[MapInputName('model_id')]
    #[MapName('model_id')]
    public mixed $model_id = null;

    #[MapInputName('service_id')]
    #[MapName('service_id')]
    public mixed $service_id;

    #[MapInputName('service_model')]
    #[MapName('service_model')]
    public ?object $service_model;

    #[MapInputName('props')]
    #[MapName('props')]
    public ?array $props = [];

    public static function after(self $data): self{
        $new = static::new();

        $service = $new->ServiceModel();
        if (isset($data->service_id)) $service = $service->findOrFail($data->service_id);
        $props['prop_service'] = $service->toViewApi()->resolve();
        return $data;
    }
}