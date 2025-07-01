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
      "reference_id"   => $this->reference_id,
      "reference_type" => $this->reference_type,
      "reference"      => $this->prop_reference,
      "status"         => $this->status,
      "price"          => isset($this->price) ? $this->price : 0,
      'margin'         => $this->margin ?? 0,
      'service_items'  => $this->relationValidation('serviceItems', function () {
        return $this->serviceItems->transform(function ($item) {
          return $item->toViewApi()->resolve();
        });
      }),
      'service_prices' => $this->relationValidation('servicePrices', function () {
        return $this->servicePrices->transform(function ($price) {
          return $price->toViewApi()->resolve();
        });
      }),
      'price_components' => $this->relationValidation('priceComponents', function () {
        return $this->priceComponents->transform(function ($price) {
          return $price->toViewApi()->resolve();
        });
      }),
      "childs"         => $this->relationValidation("childs", function () {
        return $this->childs->transform(function ($child) {
          return $child->toViewApi()->resolve();
        });
      }),
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at
    ];
    return $arr;
  }
}
