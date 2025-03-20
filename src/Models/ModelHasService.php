<?php

namespace Hanafalah\ModuleService\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;

class ModelHasService extends BaseModel
{
    use HasProps, SoftDeletes;

    protected $list  = ["id", "service_id", "reference_id", "reference_type"];
    protected $show  = [];

    public function reference()
    {
        return $this->morphTo(__FUNCTION__, $this->getTableName() . '.reference_type', $this->getTableName() . '.reference_id');
    }
    public function service()
    {
        return $this->belongsToModel('Service');
    }
}
