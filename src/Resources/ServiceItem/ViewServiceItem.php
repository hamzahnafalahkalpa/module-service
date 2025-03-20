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
                    return $this->reference->toViewApi();
               }),
               'childs'     => $this->relationValidation('childs', function () {
                    return $this->childs->transform(function ($child) {
                         return $child->toViewApi();
                    });
               }),
               'service_price' => $this->relationValidation('servicePrice', function () {
                    return $this->servicePrice->toViewApi();
               }),
               'created_at' => $this->created_at
          ];


          return $arr;
     }

     //   private function mapChildsWithDynamicKeys(array &$arr, $item) {
     //         $keyName = match ($item->flag) {
     //             Flag::MAIN_PACKAGE->value     => 'packages',
     //             Flag::CATEGORY_PACKAGE->value => 'poliKlinik',
     //             default => 'treatment'
     //         };

     //         $arr[$keyName] = $item->when(isset($item->childs), function () use ($item) {
     //             return $item->childs->map(function ($child) {
     //                 $this->load(["childs" => function ($q) {
     //                     $q->whereIn("flag", [Flag::POLI_PACKAGE->value]);
     //                 }, "reference"]);

     //                 if($child->flag == Flag::CATEGORY_PACKAGE->value) {
     //                     $child->setRelation("childs", $this->childs);
     //                     $child->load('servicePriceCategories.serviceItem');
     //                     $child->childs->map(function ($poli) use ($child) {
     //                         $servicePriceCategories = $child->servicePriceCategories->filter(function ($servicePriceCategory) use ($child) {
     //                             return $servicePriceCategory->service_category_id === $child->id;
     //                         });

     //                         $treatments = $servicePriceCategories->map(function ($servicePriceCategory) {
     //                             return (object) [
     //                                 "id"    => $servicePriceCategory->serviceItem->id,
     //                                 "name"  => $servicePriceCategory->serviceItem->name,
     //                                 "flag"  => Flag::ITEM_PACKAGE->value,
     //                                 "price" => $servicePriceCategory->price,
     //                             ];
     //                         });

     //                         $poli->setRelation("childs", $treatments);
     //                     });
     //                 }

     //                 $mappedChild = new static($child);
     //                 self::$__depth += 1;

     //                 $childArr = $mappedChild->toArray(request());
     //                 return $childArr;
     //             });
     //         });
     //   }
}
