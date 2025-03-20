<?php

namespace Gii\ModuleService\Resources\ServicePrice;

use Zahzah\LaravelSupport\Resources\ApiResource;

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
            'service'                => $this->relationValidation('service',function(){
                return $this->service->toShowApi();
            }),
            'service_item'           => $this->relationValidation('serviceItem',function(){
                return $this->serviceItem->toShowApi();
            })
        ];
        $arr = $this->mergeArray(parent::toArray($request),$arr);
         // Sort array by keys
        return $arr;
    }
}