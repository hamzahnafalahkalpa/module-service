<?php

namespace Hanafalah\ModuleService\Models;

use Hanafalah\ModuleService\Concerns\HasServicePrice;
use Hanafalah\ModuleService\Resources\ServiceItem\ShowServiceItem;
use Hanafalah\ModuleService\Resources\ServiceItem\ViewServiceItem;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceItem extends BaseModel
{
    use HasUlids, HasProps, SoftDeletes, HasServicePrice;

    public $incrementing  = false;
    protected $primaryKey = 'id';
    protected $keyType    = 'string';
    protected $list       = [
        'id',
        'parent_id',
        'service_id',
        'reference_id',
        'reference_type',
        'name',
        'price',
        'props'
    ];
    protected $show       = [];

    public function showUsingRelation(): array{
        return [
            'item',
            'childs'
        ];
    }


    public function getViewResource(){
        return ViewServiceItem::class;
    }

    public function getShowResource(){
        return ShowServiceItem::class;
    }

    //END EIGER SECTION
    public function reference(){return $this->morphTo();}
    public function service(){return $this->morphOneModel('Service', 'reference');}
    public function childs(){return $this->hasManyModel('ServiceItem', 'parent_id', 'id')->with('childs');}
}
