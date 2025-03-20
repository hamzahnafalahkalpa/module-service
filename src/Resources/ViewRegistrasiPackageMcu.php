<?php

namespace Hanafalah\ModuleService\Resources;

use Hanafalah\LaravelSupport\Resources\ApiResource;
use Hanafalah\ModuleService\Enums\ServiceItem\Flag;

class ViewRegistrasiPackageMcu extends ApiResource
{
    protected static $__depth = 0;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        $arr = [
            'id'              => $this->id,
            'name'            => $this->name ?? "Name is Invalid",
            'price'           => $this->price ?? 0,
            'flag'            => $this->flag ?? "ITEM",
            'category_id'     => $this->category_id ?? null,
            'category_name'   => $this->category_name ?? null,
            'type'            => $this->type ?? null,
            'guarantor_name'  => $this->guarantor_name ?? null,
            'guarantor_id'    => $this->guarantor_id ?? null,
            'created_at'      => $this->created_at ?? null,
        ];

        $this->mapChildsWithDynamicKeys($arr, $this);


        return $arr;
    }

    private function mapChildsWithDynamicKeys(array &$arr, $item)
    {
        $keyName = match ($item->flag) {
            Flag::MAIN_PACKAGE->value     => 'packages',
            Flag::CATEGORY_PACKAGE->value => 'poliKlinik',
            default => 'treatment'
        };

        $arr[$keyName] = $item->when(isset($item->childs), function () use ($item) {
            return $item->childs->map(function ($child) {
                $this->load(["childs" => function ($q) {
                    $q->whereIn("flag", [Flag::POLI_PACKAGE->value]);
                }, "reference"]);

                if ($child->flag == Flag::CATEGORY_PACKAGE->value) {
                    $child->setRelation("childs", $this->childs);
                    $child->load('servicePriceCategories.serviceItem');
                    $child->childs->map(function ($poli) use ($child) {
                        $servicePriceCategories = $child->servicePriceCategories->filter(function ($servicePriceCategory) use ($child) {
                            return $servicePriceCategory->service_category_id === $child->id;
                        });

                        $treatments = $servicePriceCategories->map(function ($servicePriceCategory) {
                            return (object) [
                                "id"    => $servicePriceCategory->serviceItem->id,
                                "name"  => $servicePriceCategory->serviceItem->name,
                                "flag"  => Flag::ITEM_PACKAGE->value,
                                "price" => $servicePriceCategory->price,
                            ];
                        });

                        $poli->setRelation("childs", $treatments);
                    });
                }

                $mappedChild = new static($child);
                self::$__depth += 1;

                $childArr = $mappedChild->toArray(request());
                return $childArr;
            });
        });
    }
}
