<?php

namespace Gii\ModuleService\Models;

use Gii\ModuleService\Concerns\HasServicePrice;
use Gii\ModuleService\Resources\ServiceItem\ShowServiceItem;
use Gii\ModuleService\Resources\ServiceItem\ViewServiceItem;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Zahzah\LaravelHasProps\Concerns\HasProps;
use Zahzah\LaravelSupport\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceItem extends BaseModel {
    use HasUlids, HasProps, SoftDeletes, HasServicePrice;

    public $incrementing  = false;
    protected $primaryKey = 'id';
    protected $keyType    = 'string';
    protected $list       = [
        'id','parent_id','service_id','reference_id','reference_type','name','price','props'
    ];
    protected $show       = [];

    public function toViewApi(){
        return new ViewServiceItem($this);
    }

    public function toShowApi(){
        return new ShowServiceItem($this);
    }

    //END EIGER SECTION
    public function reference(){return $this->morphTo();}
    public function service(){return $this->morphOneModel('Service','reference');}
    public function childs(){
        return $this->hasManyModel('ServiceItem','parent_id','id')->with('childs');
    }
}
