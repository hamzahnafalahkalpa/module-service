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
    $arr = [
      'id'             => $this->id,
      'service_code'   => $this->service_code,
      'name'           => $this->name,
      "reference_id"   => $this->reference_id,
      "reference_type" => $this->reference_type,
      "reference"      => $this->prop_reference,
      "status"         => $this->status,
      "price"          => $this->price,
      "cogs"           => $this->cogs,
      "margin"         => floatval($this->margin),
      'service_label'  => $this->prop_service_label,
      'service_items'  => $this->relationValidation('serviceItems', function () {
        return $this->serviceItems->transform(function ($item) {
          return $item->toViewApi()->resolve();
        });
      }),
      'service_prices' => $this->relationValidation('servicePrices', function () {
        return $this->servicePrices->transform(function ($price) {
          return $price->toViewApi();
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
