<?php

namespace Hanafalah\ModuleService\Resources\ServicePrice;

use Hanafalah\LaravelSupport\Resources\ApiResource;

class ShowServicePrice extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        $arr = [
            'service'                => $this->relationValidation('service', function () {
                return $this->service->toShowApi()->resolve();
            }),
            'service_item'           => $this->relationValidation('serviceItem', function () {
                return $this->serviceItem->toShowApi()->resolve();
            })
        ];
        $arr = $this->mergeArray(parent::toArray($request), $arr);
        // Sort array by keys
        return $arr;
    }
}
