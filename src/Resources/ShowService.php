<?php

namespace Hanafalah\ModuleService\Resources;

class ShowService extends ViewService
{
  public function toArray(\Illuminate\Http\Request $request): array
  {
    $arr = [
      'service_label'     => $this->relationValidation('serviceLabel',function(){
        return $this->serviceLabel->toShowApi()->resolve();
      }, $this->prop_service_label),
      'service_items'  => $this->relationValidation('serviceItems', function () {
        return $this->serviceItems->transform(function ($item) {
          return $item->toShowApi()->resolve();
        });
      }),
      'childs'         => $this->relationValidation('childs', function () {
        $childs = $this->childs;
        return $childs->transform(function ($child) {
          return new static($child);
        });
      })
    ];

    $arr = $this->mergeArray(parent::toArray($request), $arr);


    return $arr;
  }
}
