<?php

namespace Gii\ModuleService\Resources;

class ShowService extends ViewService
{
    public function toArray(\Illuminate\Http\Request $request) : array{
      if (request()->has('recursive')){
        $this->load('childs');
      }
      $arr = [
        'service_items'  => $this->relationValidation('serviceItems',function(){
            return $this->serviceItems->transform(function($item){
              return $item->toShowApi();
            });
        }),
        'service_prices' => $this->relationValidation('servicePrices',function(){
            return $this->servicePrices->transform(function($price){
              return $price->toShowApi();
            });
        }),
        'price_components' => $this->relationValidation('priceComponents',function(){
            return $this->priceComponents->transform(function($price){
              return $price->toShowApi();
            });
        }),
        'childs'         => $this->relationValidation('childs',function(){
          $childs = $this->childs;
          return $childs->transform(function($child){
              return new static($child);
          });
        })
      ];

      $arr = $this->mergeArray(parent::toArray($request), $arr);

      
      return $arr;
  }
}