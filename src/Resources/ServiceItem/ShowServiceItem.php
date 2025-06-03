<?php

namespace Hanafalah\ModuleService\Resources\ServiceItem;

use Illuminate\Http\Request;

class ShowServiceItem extends ViewServiceItem
{
     public function toArray(Request $request): array
     {
          $arr = [
               'reference'  => $this->relationValidation('reference', function () {
                    return $this->reference->toShowApi();
               }),
               'childs'     => $this->relationValidation('childs', function () {
                    return $this->childs->transform(function ($child) {
                         return $child->toShowApi();
                    });
               }),
               'service_price' => $this->relationValidation('servicePrice', function () {
                    return $this->servicePrice->toShowApi();
               })
          ];
          $arr = $this->mergeArray(parent::toArray($request), $arr);

          return $arr;
     }
}
