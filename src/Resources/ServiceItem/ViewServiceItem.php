<?php

namespace Hanafalah\ModuleService\Resources\ServiceItem;

use Hanafalah\LaravelSupport\Resources\ApiResource;
use Illuminate\Http\Request;

class ViewServiceItem extends ApiResource
{
     public function toArray(Request $request): array
     {
          $arr = [
               'id'         => $this->id,
               'parent_id'  => $this->parent_id,
               'name'       => $this->name ?? "Unknown",
               'price'      => $this->price ?? 0,
               'flag'       => $this->flag,
               'reference'  => $this->relationValidation('reference', function () {
                    return $this->reference->toViewApi()->resolve();
               }),
               'childs'     => $this->relationValidation('childs', function () {
                    return $this->childs->transform(function ($child) {
                         return $child->toViewApi()->resolve();
                    });
               }),
               'service_price' => $this->relationValidation('servicePrice', function () {
                    return $this->servicePrice->toViewApi()->resolve();
               }),
               'created_at' => $this->created_at,
               'updated_at' => $this->created_at
          ];


          return $arr;
     }
}
