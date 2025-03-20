<?php

namespace Hanafalah\ModuleService\Resources;

use Hanafalah\LaravelSupport\Resources\ApiResource;

class ViewService extends ApiResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray(\Illuminate\Http\Request $request): array
  {
    if ($request->has('is_recursive')) {
      $this->load("childs");
    }
    $arr = [
      'id'             => $this->id,
      'name'           => $this->name,
      "status"         => $this->status,
      "reference_id"   => $this->reference_id,
      "reference_type" => $this->reference_type,
      "status_spell"   => ($this->status) ? "Active" : "Inactive",
      "price"          => isset($this->price) ? $this->price : 0,
      'margin'         => $this->margin ?? 0,
      'service_items'  => $this->relationValidation('serviceItems', function () {
        return $this->serviceItems->transform(function ($item) {
          return $item->toViewApi();
        });
      }),
      'service_prices' => $this->relationValidation('servicePrices', function () {
        return $this->servicePrices->transform(function ($price) {
          return $price->toViewApi();
        });
      }),
      'price_components' => $this->relationValidation('priceComponents', function () {
        return $this->priceComponents->transform(function ($price) {
          return $price->toViewApi();
        });
      }),
      "reference"      => $this->relationValidation("reference", function () {
        switch ($this->reference_type) {
          case $this->MedicServiceModel()->getMorphClass():
            $medic_service = $this->reference;
            return [
              'id'     => $medic_service->getKey(),
              'color'  => $medic_service->color,
              'flag'   => $medic_service->flag
            ];
            break;
          default:
            return $this->reference;
        }
      }),
      "childs"         => $this->relationValidation("childs", function () {
        return $this->childs->transform(function ($child) {
          return $child->toViewApi();
        });
        // $childs = $this->childs;
        // return $childs->map(function($child){
        //     return new static($child);
        // });
      }),
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at
    ];
    $props = $this->getPropsData();
    if (isset($props) && count($props) > 0) {
      foreach ($props as $key => $prop) {
        if ($key == 'price') continue;
        $arr[$key] = $prop;
      }
    }

    return $arr;
  }
}
